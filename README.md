# Rest API for Hatimeria

## Requirements

Write a php application (using any framework of your choosing, ie. symfony, larvel) application that acts as a REST API. The application should have the following endpoints

### GET /
    - no input data needed
    - return value is a json `{ version: ${versionNumber} }`
    - version number should be read from `composer.json` file

### GET /spotify
    - no input data needed
    - return value is a json with tracks from Hatimeria spotify playlist in a format (https://open.spotify.com/playlist/63ueJyeWHgUTEa57QUjmfI):
```
{
            tracks: int // number of tracks on the playlist,
            items: [{
                title: string //song title,
                authors: [{
                    name: string //artist name
                }]
            }]
        }
```
    - spotify docs are available here: https://developer.spotify.com/documentation/web-api/
    - token for usage: client ID: `406d38031a3d4eab9361b281c1252b45` client secret: `28c0c3cc71844300a19954fabca037ce` and use `client_credentials` as a grant type
    - you may use any available library as long as it’s license is not proprietary

### GET|POST /products
    - require access token:
    - value may be hardcoded into the .env file or config file that it outside of version control
    - value may be generated as oauth 2.0 token/bearer after request to separate token endpoint
    - provide info how to set the value for the token/token generation
    - perform a GET request to external API endpoint https://next.therake.com/api/ext/jam/search
    - that endpoint accepts the following input data are passed as query string, possible fields are:

        type: products|posts|all (default)
        include_product: string (comma-separated list of fields returned for products, default to use: ‚name,thumbnail,price,brand,url_key,option_text_product_tag,sku,tax_class_id’)
        include_post: string (comma-separated list of fields returned for post, default to use: post_id,post_title,post_date,permalink)
        size: int (number of items per page)
        productPage: int (product search result page number)
        postPage: int (post search result page number)
        search: string (search term)

    - as a result return only search result data, without filters and pagination data in the structure (if result from API does not have required value for the field endpoint should return null for that field):
```
    {
        products: [{
            name: string,
            thumbnail: string,
            price: float
        },...],
        posts: [{
            post_title: string,
            post_date: string,
            thumbnail: string
        },...]
    }
```
    - structure of this endpoint does not need to match structure of remote API endpoint, please provide info about endpoint input structure

## Installation instructions

1. Clone the repository.
1. Run `composer install`.
1. You need to provide Token for authentication in /products endpoint.
This token should be provided as `TOKEN` environment variable. 
The easiest way to do it is to create `.env.local` file in the main project directory.
Also you should add variable for /spotify endpoint with client credentials: `APP_SPOTIFY_CLIENT_ID=406d38031a3d4eab9361b281c1252b45` and `APP_SPOTIFY_CLIENT_SECRET=28c0c3cc71844300a19954fabca037ce`.
1. Run this application on any web server. For example, [Symfony Local Web Server](https://symfony.com/doc/current/setup/symfony_server.html#getting-started) is enough.
1. Run `composer tests` to run all tests.

## Endpoints
1. GET /
    
    That endpoint returns json with version from composer.json. If that key doesn't exist, json will be empty.
1. GET /spotify
    
    That endpoint returns list of tracks from Hatimeria's playlist in Spotify service.
    Steps:
    - retrieving token based on client Id and client secret
    - retrieving playlist with bearer authorization using token from step first
    - deserialization response from Spotify Api using Symfony Serializer to DTO
    - transforming DTO to value objects, which will be returning in controller
    - whole process can use another profile Id
1. GET /products
    
    That endpoint returns products and posts from The Rake Api with method search based on search term.
    Endpoint has query parameters like:
    - search - characters which will be searching
    - type - products, posts or all (default: all)
    - includeProduct - list with product's fields, which will be returning in response, format: array with string items (default: [name,thumbnail,price,brand,url_key,option_text_product_tag,sku,tax_class_id])
    - includePost - list with post's fields, which will be returning in response, format: array with string items (default: [post_id,post_title,post_date,permalink])
    - size - number of items per page
    - productPage - product search result page number
    - postPage - post search result page number
    Step:
    - creating request with query parameters
    - adding X-AUTH-TOKEN Header with token value from env.local
    - retrieving list of products and posts from The Rake Api
    - deserialization response from Spotify Api using Symfony Serializer to DTO
    - transforming DTO to value objects, which will be returning in controller
    - DTO may have null values
    

