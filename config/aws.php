<?php

return [
    // check if endpoint is provided in the .env otherwise build a default region based one.
    // use http://localhost:8000 in .env when running local DynamoDb container
    'endpoint' => env(
        'AWS_ENDPOINT',
        sprintf('https://apigateway.%s.amazonaws.com', env('AWS_REGION'))
    ),
];
