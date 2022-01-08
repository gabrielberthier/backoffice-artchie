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

Validar estados:

1. JWT valido + Refresh Valido
2. JWT valido + Refresh Inválido
3. JWT inválido + Refresh Valido
4. JWT invalido + Refresh invalido
