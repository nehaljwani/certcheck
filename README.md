# certcheck
Small Web-UI to check SSL Certs

Create a file by the name `domains.txt` with `$domain:$port` on each line. For example:
```bash
$ cat domains.txt
imap.mail.yahoo.com:993
smtp.gmail.com:465
facebook.com:443
```

Run the server
```bash
$ php -S 127.0.0.1:8080 
```

Visit [http://localhost:8080](http://localhost:8080) in your browser.

![screenshot from 2017-03-03 11-01-06](https://cloud.githubusercontent.com/assets/1779189/23539360/cb23276a-0000-11e7-8df5-c357a0d9861b.png)
