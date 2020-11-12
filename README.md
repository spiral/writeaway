PHP API server for Writeaway editor
========

License:
--------
MIT License (MIT). Please see [`LICENSE`](./LICENSE) for more information. Maintained by [Spiral Scout](https://spiralscout.com).


## API Endpoints:
### List Images
**GET** or **POST** `writeaway:images:list` to fetch a full list of available images.

Example response:
```json
{
  "status": 200,
  "data": [      
    {
      "id": "unique-id",
      "src": "image1.png"
    },
    {
      "id": "unique-id",
      "thumbnailSrc": "image2-th.png",
      "src": "image2.png"
    }
  ]
}
```
Possible image fields:
| Field | Type | Required | Description  |
| :--- | :--- | :--- | :--- |
| id | string | **Required** | Image id |
| src | string | **Required** | Image source URL |
| thumbnailSrc | string | Optional | Image thumbnail URL |
| height | number | Optional | Image height to display |
| width | number | Optional | Image width to display |

### Upload Image
**POST** `writeaway:images:upload` to upload an image file.

Example request:

`image` - FormData file

Example response: 
```json
{
  "status": 200,
  "data": [      
    {
      "id": "unique-id",
      "src": "image1.png"
    }
  ]
}
```
> For possible image fields see the previous endpoint.

### Delete Image
**POST** or **DELETE** `writeaway:images:delete` to delete a particular image

Example request:
```json
{
  "id": "unique-id"
}
```
Example response: 
```json
{
  "status": 200
}
```

### Get Piece
**GET** or **POST** `writeaway:pieces:get` to fetch a particular piece by its `id` and `type`.

Example request:
```json
{
  "id": "unique-id",
  "type": "piece-type"
}
```
> If no pieces found, a new one wil be created. `id` is a unique value across all pieces.

Example response:
```json
{
  "status": 200,
  "data": {
    "id": "unique-id",
    "type": "piece-type",
    "data": {
      "key": "value",
      "key...": "value..."
    }
  }
}
```
In case if validation errors the example response will be:
```json
{
  "status": 400,
  "errors": {
    "field-name": "error-message",
    "field-name...": "error-message..."
  }
}
```

### Get Pieces in bulk
**GET** or **POST** `writeaway:pieces:bulk` to fetch a particular pieces by theirs `id` and `type`.

Example request:
```json
{
  "pieces": [
    {
      "id": "unique-id",
      "type": "piece-type"
    },
    {
      "id": "unique-id",
      "type": "piece-type"
    }
  ]
}
```

Example response:
```json
{
  "status": 200,
  "data": [ 
    {
      "id": "unique-id",
      "type": "piece-type",
      "data": {
        "key": "value",
        "key...": "value..."
      }
    },
    {
      "id": "unique-id",
      "type": "piece-type",
      "data": {
        "key": "value",
        "key...": "value..."
      }
    }
  ]
}
```
> Not found pieces will be ignored. 

In case if validation errors the example response will be:
```json
{
  "status": 400,
  "errors": {
    "field-name": "error-message",
    "field-name...": "error-message..."
  }
}
```

### Save Piece
**POST** `writeaway:pieces:save` to save a particular piece by its `id` and `type`.

Example request:
```json
{
  "id": "unique-id",
  "type": "piece-type",
  "data": {
    "key": "value",
    "key...": "value..."
  }
}
```
> If no pieces found, a new one wil be created. `id` is a unique value across all pieces.

Example response:
```json
{
  "status": 200,
  "data": {
    "id": "unique-id",
    "type": "piece-type",
    "data": {
      "key": "value",
      "key...": "value..."
    }
  }
}
```
In case if validation errors the example response will be:
```json
{
  "status": 400,
  "errors": {
    "field-name": "error-message",
    "field-name...": "error-message..."
  }
}
``` 

## Components
### Meta
Meta is a structure designed to represent current piece editor. While this package knows nothing about real app actors,
`\Spiral\Writeaway\Service\Meta\ProviderInterface` is given - a developer can bind it to a more rich implementation,
so the meta will contain the real user's id, label and time. Example:
```json
{
  "id": "some user id",
  "label": "some user label, such as name",
  "time": "current date time string"
}
```
