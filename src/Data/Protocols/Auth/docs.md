# What should loginService do

- Receive an account
- Check if account is valid and email exists in database
- If not: throws AccountNotFoundException
- Compare account object's password with database **hashed** password
  - If accountObjectPswd !== hash: throw InvalidPasswordException
  - Else:
    - Return JWT and hashed renew token
