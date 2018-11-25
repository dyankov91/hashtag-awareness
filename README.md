# Hashtag Awareness App

The app is doing a real time monitoring of a Social Media feed for given keyword. 
Based on this monitoring the app is highlighting in a graph model the relationships between the authors of the posts.
The app is also looking for anomalies in the data of the social media posts and alerts when such are detected.

## Install

..

## Configuration
After you install the app you need to set some configurations in order for it to be able to do its job.

Rename the `.evn.example` file to `.env` and set up your environment variables.

## How to Start Monitoring
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

### Twitter Driver
In order to use the `twitter` driver to monitor the Twitter feed you need to set the following in your `.env` file.
You can obtain your values from http://developer.twitter.com.

```dotenv
TWITTER_CONSUMER_KEY=[consumer-key]
TWITTER_CONSUMER_SECRET=[cosumer-secret]
TWITTER_OAUTH_TOKEN=[token]
TWITTER_OAUTH_SECRET=[secret]
```
