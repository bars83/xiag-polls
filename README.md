* English :point_left:
* [Russian](README.ru.md)

# "XIAG Polls" application structure

## The following component level structure is planned:
* Use ``php-fpm`` + ``Twig`` + ``MySQL`` + ``nginx`` for backend;
* ``React``, ``webpack`` for frontend;
* Install required packages and libraries via ``composer`` and ``yarn``; 
* Setup ``nginx`` response caching for load reduction of backend in case of DoS attack;
* Run components with [docker containers](https://docs.docker.com/install/), component linking must be declared in [docker-compose](https://docs.docker.com/compose/install/) file, to have an immutable components state in others environments.

## Application level structure is planned with following order:
* Input form markup can be taken from example pages:
    * [poll creation form](https://test-task.xiag.ch/fullstack-developer__example1.html)
    * [poll answer form with results table](https://test-task.xiag.ch/fullstack-developer__example2.html)
* After user enters a question and possible answers, data is sended to server side;
* On server side after data validation, records stores to tables "Poll" for polls details and "Answer" for possible answers. Server response contains data with created poll identifier (uid);
* After server resnonse with new poll identifier, client going to poll page. May be it make sense to get user agreement to set cookie files (GDRP);
* User set his/her name, select preffered option and submit form. Btw, task description says nothing about mandatory of setting any answer in poll, but just word concept of "poll" implies that answer must be always given;
* Cookie must be checked before form submitting to check user have no previous given answers for this poll (may be in other browser tab);
* In the bottom of page there is current voting results table, refreshed by timer;
* On client request server stores answer in DB and return succesful response with current voting results;
* On server succeful response client sets cookie with poll identifier to set user already answers this poll. Current voting results table is updating with data from server response.
