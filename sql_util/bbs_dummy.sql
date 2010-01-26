INSERT INTO bbs_db.comments (user_id, facility_id, date, ip, host, text)
  VALUES
    (1, 2, NOW(), INET_ATON("192.168.1.0"), "titech.ac.jp", "hogehoge")
    ,(1, 2, NOW(), INET_ATON("127.0.0.1"), "cyb.mei.titech.ac.jp", "hello!")
;
