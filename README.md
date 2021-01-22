
# Laravel PubSub
This project aims to be a study, and when necessary a test facilitator in the local environment who has communication with PubSub.

## Requirements
- Docker 19.03.8
- Docker Compose 1.25.0

<b>Ps: </b> Or equivalent summers

## How to use
- Create and listen to the topic
```
docker exec -it laravel-pubsub_laravel_1 php artisan pubsub:pull name-topic
```
- Send messages
```
docker exec -it laravel-pubsub_laravel_1 php artisan pubsub:push mensagens --message='text message' --path='exemp.json'
```
