<?php
/*
* ┏━━━┓╋╋╋╋╋┏┓┏━━━━┓
* ┃┏━┓┃╋╋╋╋┏┛┗┫┏┓┏┓┃
* ┃┗━┛┣━━┳━┻┓┏╋┫┃┃┣┻━┳━━┳┓┏┓
* ┃┏┓┏┫┏┓┃┏┓┃┃┣┫┃┃┃┃━┫┏┓┃┗┛┃
* ┃┃┃┗┫┗┛┃┗┛┃┗┫┃┃┃┃┃━┫┏┓┃┃┃┃
* ┗┛┗━┻━━┻━━┻━┻┛┗┛┗━━┻┛┗┻┻┻┛
* @author: David Ratnikov
* @website: https://github.com/ddosnikgit
*/

namespace ddosnik\utils;

use ddosnik\VKCli;

final class VKLib {
    private static $instance = null;
    public array $users = array();
    private array $poll = array();

    /**
     * @var VKCli
     */
    private $main;

    public function __construct(VKCli $main) {
        $this->main = $main;
        static::$instance = $this;
    }

    public function tokenisValid() : bool {
        $request = Utils::sendRequest('account.setOnline',  array(
            'access_token' => $this->main->getToken(),
            'v' => 5.173
        ));
        if(isset($request['error'])) {
            return false;
        } else {
            return true;
        }
    }

    public function registerStatic() {
      if(static::$instance === null){
        static::$instance = $this;
      }
    }

    /**
     * @return VKLib|null
     */
    public static function getInstance(): ?VKLib
    {
        return static::$instance;
    }

    public function choiseChat() : void {
      $this->main->getLogger()->info('Получаем ваши диалоги...');
      $user_ids = ''; // TODO: Айдишники пользователей из диалогов, для избежания флуд контроля от вк.
      $request = Utils::sendRequest('messages.getConversations', array(
        'access_token' => $this->main->getToken(),
        'v' => 5.173,
        'count' => 20
      ));
      foreach($request['response']['items'] as $item) {
        if(isset($item['conversation']['chat_settings'])) {
        $this->main->getLogger()->info('Беседа '.$item['conversation']['chat_settings']['title'].' (ID: '. (int)$item['conversation']['peer']['id'] - 2000000000 .')');
      }else if($item['conversation']['peer']['type'] === 'user') {
        $user_ids .= $item['conversation']['peer']['id'].', ';
      }
    }
    if(strlen($user_ids) > 0) {
      foreach($this->getNameAndSurname($user_ids) as $user) {
        $this->main->getLogger()->notice('Пользователь '.$user[0].' (ID: '.$user[1].')');
         }
      }
  }

    public function getGroupInfo(int $id) : ?array {
      $request = Utils::sendRequest('groups.getById', array(
        'access_token' => $this->main->getToken(),
        'v' => 5.173,
        'group_id' => abs($id)
      ));
      if(!isset($request['error'])) {
        return array($request['response']['groups'][0]['name'], $request['response']['groups'][0]['screen_name']);
      }
      return null;
    }

    public function getNameAndSurname(string $ids) {
      $request = Utils::sendRequest('users.get', array(
        'access_token' => $this->main->getToken(),
        'v' => 5.173,
        'user_ids' => $ids
      ));
      if(!isset($request['error'])) {
          if(sizeof($request['response']) > 1) {
            $this->users = array();
            foreach($request['response'] as $user) {
              $this->users[$user['id']] = array($user['first_name'].' '.$user['last_name'], $user['id']);
            }
            return $this->users;
          } else {
            return $request['response'][0]['first_name'].' '.$request['response'][0]['last_name'];
          }
          return null;
        }
      }

    public function getPollServer() : ?array {
      $request = Utils::sendRequest('messages.getLongPollServer', array(
        'access_token' => $this->main->getToken(),
        'v' => 5.173
      ));
      if(!isset($request['error'])) {
        $this->poll = array($request['response']['server'], $request['response']['key'], $request['response']['ts']);
        return array($request['response']['server'], $request['response']['key'], $request['response']['ts']);
      }
      return null;
    }

    public function toFormat(string $message) : string {
      $message = str_replace('<br>', PHP_EOL, $message);
      return $message; //TODO: будет пополняться, если есть желание, можете помочь, буду благодарен :).
    }

    public function getShortLink(int $id) : ?string {
      $request = Utils::sendRequest('users.get', array(
        'access_token' => $this->main->getToken(),
        'v' => 5.173,
        'fields' => 'screen_name',
        'user_ids' => $id
      ));
      if(!isset($request['error'])) {
        return $request['response'][0]['screen_name'];
      }
      return null;
    }

    public function getAccountInfo() : ?array {
      $request = Utils::sendRequest('users.get', array(
        'access_token' => $this->main->getToken(),
        'v' => 5.173,
        'fields' => 'screen_name',
      ));
      if(!isset($request['error'])) {
        return array($request['response'][0]['first_name'], $request['response'][0]['last_name'], $request['response'][0]['screen_name']);
      }
      return null;
    }

    public function updateMessages() : void {
      while(true) {
        $this->main->updateConsole();
        $req = Utils::sendRequest('', array(
          'act' => 'a_check',
          'key' => $this->poll[1],
          'ts' => $this->poll[2],
          'wait' => 1,
          'mode' => 2,
          'version' => 3
        ), 'https://'.$this->poll[0]);
         $this->poll[2] = $req['ts'];
         foreach($req['updates'] as $update) {
           if(isset($update[5]) && isset($update[6]) && isset($update[3]) && isset($update[6]['from'])) {
             if($update[3] === $this->main->peer_id) {
               if($update[6]['from'] < 0) {
                 $groupInfo = $this->getGroupInfo($update[6]['from']);
                 $this->main->getLogger()->msg($groupInfo[0], $this->toFormat($update[5]), $this->main->shortlink, $groupInfo[1]);
               } else {
                 $this->main->getLogger()->msg($this->getNameAndSurname($update[6]['from']), $this->toFormat($update[5]), $this->main->shortlink, $this->getShortLink($update[6]['from']));
               }
             }
           }
         }
       }
     }

    public function sendMessage(string $message, int $peer_id) : void{
        $request = Utils::sendRequest('messages.send', array(
            'peer_id' => $peer_id,
            'access_token' => $this->main->getToken(),
            'message' => $message,
            'v' => 5.173,
            'random_id' => 0
        ));
        if(isset($request['error'])) {
            $this->main->getLogger()->error('Произошла ошибка при отправке сообщения [TEXT: '.$message.', PEER_ID: '.$peer_id.']: '.$request['error']['error_msg']);
        } else {
          //  $this->main->getLogger()->notice('Сообщение [TEXT: '.$message.', PEER_ID: '.$peer_id.'] успешно отправлено.');
        }
    }
}
