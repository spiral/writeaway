PHP API server for WriteAway editor
========

License:
--------
MIT License (MIT). Please see [`LICENSE`](./LICENSE) for more information. Maintained by [Spiral Scout](https://spiralscout.com).


## API Endpoints:
### List
**GET** or **POST** `writeAway:images:list` to fetch a full list of available images.

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

### Upload
**POST** `writeAway:images:upload` to upload an image file.

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

### Delete
**POST** or **DELETE** `writeAway:images:delete` to delete a particular image

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
