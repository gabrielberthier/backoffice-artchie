insert into
  accounts(
    email,
    username,
    password,
    role,
    uuid,
    created_at,
    updated_at
  )
values
  (
    'adming@arcthie.com',
    'admin',
    '$2y$08$dBhkPXXqtiZoRyFQ5BIfaOVe2pbdd03lZaEUnux9pNcbGf4/5epoe',
    'admin',
    '0c04d7f5-5e42-4fdd-9ba2-d1b44cd22ac9',
    now(),
    now()
  )