# jacek.codes
Useless programming and general ramblings.

## Development database
The site uses a MySQL database for storing data which will need to be set up locally for any development.

First create the database with
~~~sql
CREATE SCHEMA jacek_codes DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
~~~

Update the `phinx.yml` file to point to the new database then run the migrations with
~~~bash
./vendor/bin/phinx migrate
~~~


## Activity sources
| Name                      | Description                                   | Implemented |
| ------------------------- | --------------------------------------------- | ----------- |
| GitLab and GitHub commits | Commit message and file/line stats            | No          |
| GitHub merge requests     | New commits and status changes                | No          |
| Blog posts                | Standard blog posts                           | No          |
| Links                     | Links to other sites with a short description | No          |
| CodePen                   | New pens created                              | No          |

## Fun activity sources
| Name                      | Description                                   | Implemented |
| ------------------------- | --------------------------------------------- | ----------- |
| Spotify                   | Daily summary and new playlists               | No          |
| Netflix and Kodi          | TV and movies watched                         | No          |
