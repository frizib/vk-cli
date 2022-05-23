# VK-CLI
VK Messenger in your Linux Terminal

PHP8 Installation
-------------
- `wget https://jenkins.pmmp.io/job/PHP-8.0-Aggregate/lastSuccessfulBuild/artifact/PHP-8.0-Linux-x86_64.tar.gz`
- `tar -xvf PHP-8.0-Linux-x86_64.tar.gz`

Launch
-------------
- `apt install git && git clone https://github.com/ddosnikgit/vk-cli`
- `cd vk-cli && chmod 777 start.sh`
- `bash start.sh`

Settings
-------------
- `sed -i 's/zd3afa3466720b6a3791fffffff975aa98616f6abb574mnnnn976afd497482084cabb4c74f6h6/YOU_API_TOKEN/g' settings/config.yml`
