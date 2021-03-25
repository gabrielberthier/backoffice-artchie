# What to do?

Flux
: Determine how login works properly

- Receive user login data (i.e, username, password, email)
- Validate it
- If validation works then
  - Get user from db using username and email (:may throw error)
  - Has user?
  - Compare provided password and db hash
    - Passwords match?
      - Yes: 
        - send 200
        - send jwt
        - send HttpOnly cookie with refresh-token
      - No
        - send 401
- Else
  - Has empty data: yes -> 400
  - Return 422 with reasons case error is mistake (i.e. wrong email, password does not contemplait rules)
  