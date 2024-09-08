# Music Library Cleaner

Attempts to clean up your folder of mp3 files into organized folders and tag them appropriately.
Uses LastFM api to search for information about your files.

Requirements:
1. docker

Steps:
1. Get a [LastFM API Key](https://www.last.fm/api/authentication)
2. Copy `.env.sample` to `.env` and put your credentials
3. Build the sqliteDB (see below)
4. Run the cleaner (see below)

## Build the database
```
docker-compose run --rm db
```

## Run

Update `docker-compose.yaml` and update the path mapping to `/music`

In my case `/Users/kyle/Music/testmusic:/music` means my local music folder is at `/Users/kyle/Music/testmusic`  

```
docker compose run --rm app
```

TODO: actual moving of files
```
docker compose run --rm app move
```

## Limitations
There are some tracks that are incorrectly tagged even in LastFM

For example: https://ws.audioscrobbler.com/2.0?track=eric+clapton+boyce+avenue+acoustic+cover+wonderful+tonight&format=json&api_key=<API_KEY>&method=track.search returns a reversed tag result

```json
{
  "results": {
    "opensearch:Query": {
      "#text": "",
      "role": "request",
      "startPage": "1"
    },
    "opensearch:totalResults": "5",
    "opensearch:startIndex": "0",
    "opensearch:itemsPerPage": "30",
    "trackmatches": {
      "track": [
        {
          "name": "Eric Clapton (Boyce Avenue acoustic cover) on Spotify & Apple",
          "artist": "Wonderful tonight",
          "url": "https://www.last.fm/music/Wonderful+tonight/_/Eric+Clapton+(Boyce+Avenue+acoustic+cover)+on+Spotify+&+Apple",
          "streamable": "FIXME",
          "listeners": "16",
          "image": [
            {
              "#text": "",
              "size": "small"
            },
            {
              "#text": "",
              "size": "medium"
            },
            {
              "#text": "",
              "size": "large"
            },
            {
              "#text": "",
              "size": "extralarge"
            }
          ],
          "mbid": ""
        },
        {
          "name": "Wonderful Tonight - Eric Clapton (Boyce Avenue acoustic cover) on Spotify & Apple",
          "artist": "Boyce Avenue",
          "url": "https://www.last.fm/music/Boyce+Avenue/_/Wonderful+Tonight+-+Eric+Clapton+(Boyce+Avenue+acoustic+cover)+on+Spotify+&+Apple",
          "streamable": "FIXME",
          "listeners": "4",
          "image": [
            {
              "#text": "https://lastfm.freetls.fastly.net/i/u/34s/2a96cbd8b46e442fc41c2b86b821562f.png",
              "size": "small"
            },
            {
              "#text": "https://lastfm.freetls.fastly.net/i/u/64s/2a96cbd8b46e442fc41c2b86b821562f.png",
              "size": "medium"
            },
            {
              "#text": "https://lastfm.freetls.fastly.net/i/u/174s/2a96cbd8b46e442fc41c2b86b821562f.png",
              "size": "large"
            },
            {
              "#text": "https://lastfm.freetls.fastly.net/i/u/300x300/2a96cbd8b46e442fc41c2b86b821562f.png",
              "size": "extralarge"
            }
          ],
          "mbid": ""
        },
        {
          "name": "Eric Clapton on Spotify & Apple (Boyce Avenue acoustic cover)",
          "artist": "Wonderful tonight",
          "url": "https://www.last.fm/music/Wonderful+tonight/_/Eric+Clapton+on+Spotify+&+Apple+(Boyce+Avenue+acoustic+cover)",
          "streamable": "FIXME",
          "listeners": "2",
          "image": [
            {
              "#text": "",
              "size": "small"
            },
            {
              "#text": "",
              "size": "medium"
            },
            {
              "#text": "",
              "size": "large"
            },
            {
              "#text": "",
              "size": "extralarge"
            }
          ],
          "mbid": ""
        },
        {
          "name": "Eric Clapton  🎵 (Boyce Avenue acoustic Cover)",
          "artist": "Wonderful tonight",
          "url": "https://www.last.fm/music/Wonderful+tonight/_/Eric+Clapton++%F0%9F%8E%B5+(Boyce+Avenue+acoustic+Cover)",
          "streamable": "FIXME",
          "listeners": "1",
          "image": [
            {
              "#text": "",
              "size": "small"
            },
            {
              "#text": "",
              "size": "medium"
            },
            {
              "#text": "",
              "size": "large"
            },
            {
              "#text": "",
              "size": "extralarge"
            }
          ],
          "mbid": ""
        },
        {
          "name": "Wonderful Tonight - Eric Clapton (Boyce Avenue acoustic Cover) (Lyrics)🎵",
          "artist": "EmboLyrics",
          "url": "https://www.last.fm/music/EmboLyrics/_/Wonderful+Tonight+-+Eric+Clapton+(Boyce+Avenue+acoustic+Cover)+(Lyrics)%F0%9F%8E%B5",
          "streamable": "FIXME",
          "listeners": "1",
          "image": [
            {
              "#text": "",
              "size": "small"
            },
            {
              "#text": "",
              "size": "medium"
            },
            {
              "#text": "",
              "size": "large"
            },
            {
              "#text": "",
              "size": "extralarge"
            }
          ],
          "mbid": ""
        }
      ]
    },
    "@attr": {}
  }
}
```

In this example we have no reliable way of finding out the real artist. We can guess by checking if the album cover is provided (image has value)
