
## Dataak PHP Challenge


##### After clone the project

##### First run:
```
docker-compose up -d
```
Or if you have installed sail
```
./vendor/bin/sail up -d
```
And after that:
```
docker-compose composer install
or
./vendor/bin/sail composer install
```
###### .env changes

Set below items into `.env` file
```
FORWARD_DB_PORT=33060
APP_PORT=8000
ELASTICSEARCH_ENABLED=true
ELASTICSEARCH_HOSTS="http://elasticsearch:9200"
```

#### Migrate 

```
docker-compose php artisan migrate:fresh --seed
or
./vendor/bin/sail artisan migrate:fresh --seed
```

### API endpoints
There is a postman workspace Json file https://github.com/HamidBaseri/dataak-tsk/blob/master/Dataak-tsk.postman_collection.json
Or

#### Get all tweets
Get http://localhost:8000/api/tweets
for search use below parameters in query_string
or without query_string to return all items
```
q
date
```
q is for search query
date is in format 'dd-MM-yyyy' to search items after this date

#### Create new tweet
POST http://localhost:8000/api/tweets
request with below parameters
```
avatar
image
body
username
retweets
```

#### Get all instagram posts
Get http://localhost:8000/api/instagrams
for search use below parameters in query_string
or without query_string to return all items

```
q
date
```
q is for search query
date is in format 'dd-MM-yyyy' to search items after this date

#### Create new instagram post
POST http://localhost:8000/api/instagrams
request with below parameters
```
avatar
filenames
body
username
title
name
```


#### Get all news
Get http://localhost:8000/api/news
for search use below parameters in query_string
or without query_string to return all items

```
q
date
```
q is for search query
date is in format 'dd-MM-yyyy' to search items after this date

#### Create new news
POST http://localhost:8000/api/news
request with below parameters
```
avatar
body
title
source
src
```

#### Create new alert
POST http://localhost:8000/api/alerts
request with below parameters
```
source
```
source is a news agancy source name

