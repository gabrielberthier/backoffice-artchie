# Login authentication flux

Funcionamento
: Login

- Recebe data input
- Valida os dados
- Se a validação passar:
  - Pegar usuário do banco utilizando email ou username
  - Se o usuário existir:
    - Comparar senha provida pelo banco
    - Se as senhas são compatíveis:
      - Retorna JWT
      - Retorna Refresh Token HTTPOnly
      - Retorna 200
    - Senão:
      - Retorna erro (PasswordsNotMatch)
      - Retorna 401
  - Senão:
    - Retorna erro (UserNotFound)
    - Retorno 401
- Senão:
  - Retorna erros de validação (1..n)
  - Retorna 400
