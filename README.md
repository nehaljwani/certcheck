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

![screenshot from 2017-01-09 23-52-23](https://cloud.githubusercontent.com/assets/1779189/21777930/31b617b6-d6c7-11e6-8dc5-766057fec8cf.png)
