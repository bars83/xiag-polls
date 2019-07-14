### XIAG Poll test task by bars83
[![Build Status](https://travis-ci.org/bars83/xiag-polls.png)](https://travis-ci.org/bars83/xiag-polls)


## System requirements 
 - Docker ([how to install](https://docs.docker.com/install/))
 - Docker Compose ([how to install](https://docs.docker.com/compose/install/))


## Installation instructions
 - ``git clone https://github.com/bars83/xiag-polls``
 - ``cd xiag-polls/docker``
 - ``cp .env.dist .env``
 - set valid PORT for web server in ``.env`` file
 - ``docker-compose up -d``
 - go to ``http://localhost:PORT``  

## How to stop and clear  
 - run ``docker-compose down -v`` in project ``docker`` dir
 - ``rm -rf {project root dir}``  
 