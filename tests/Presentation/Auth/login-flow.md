# What to do?

---

- get request -> validate JWT
  - isValid JWT?
    - get resource + 200
  - else
    - get HttpOnly refresh token
    - isValid
      - generate new jwt token and send it in header + 200
    - else
      - send error + 401
