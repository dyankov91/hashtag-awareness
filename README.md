# Hashtag Awareness App

The app is doing a real time monitoring of a Social Media feed for given keyword. 
Based on this monitoring the app is highlighting in a graph model the relationships between the authors of the posts.
The app is also looking for anomalies in the data of the social media posts and alerts when such are detected.

## Run locally

First clone the `dyankov91\hashtag-awareness-devstack` repo and follow the instructions there in order to prepare the needed environemtn for the project.

Once the enviroment is ready find the newly created `app` directory and clone this repo inside it. After run composer in order to install project dependencies.

```sh
$ composer install 
```

Once this finish proceed to the configuration step.

## Configuration
After you install the app you need to set some configurations in order for it to be able to do its job.

Rename the `.evn.example` file to `.env` and set up your environment variables. See `Monitoring Drivers` section for details on how to configure for different social networks drivers.

## How to start monitoring
```$sh
   $ php artisan monitoring:start [driver] --keywords[=KEYWORDS]
```

Where `[driver]` is the name of the social media driver which to be used for monitoring process.
This can be one of the following: `twitter`

Use the option `--keywords` to provide keywords for which the monitoring process to start.

```$xslt
    $ php artisan monitoring:start twitter --keywords=#blackfriday
    
    # short syntax
    $ php artisan monitoring:start twitter -k#blackfriday
    
    # two keywords - '#blackfirday' and 'sales'
    $ php artisan monitoring:start twitter -k#blackfriday -ksales
    
    # help
    $ php artisan monitoring:start -h
```

### Monitoring drivers

#### Twitter driver
In order to use the `twitter` driver to monitor the Twitter feed you need to set the following in your `.env` file.
You can obtain your values from http://developer.twitter.com.

```dotenv
TWITTER_CONSUMER_KEY=[consumer-key]
TWITTER_CONSUMER_SECRET=[cosumer-secret]
TWITTER_OAUTH_TOKEN=[token]
TWITTER_OAUTH_SECRET=[secret]
```

## How to process and store the fetched data

Once the monitoring process is started it is going monitor the given social media. In order for the data reading to be done as fast as possible, the only thing that the monitor process is going to do with it, is to put it on a queue for further processing and storing async operations .

In order to process the queue use the `php artisan queue:work` command. (For long living processes (in production) it need to be run with supervisor.)

 You can also use the `php artisan queue:listen` in order to see the procceesed itemes on the queue. Note that both commands are standart `Laravel` queue commands so you can see more details in the laravel documentation on how to use the queue.
