# Command Overview

Doctrine console
: The Doctrine Console is a Command Line Interface tool for simplifying common administration tasks during the development of a project that uses ORM.

> To use [Doctrine Command Line](https://www.doctrine-project.org/projects/doctrine-orm/en/2.8/reference/tools.html) just enter **vendor/bin/doctrine**

### The following Commands are currently available:

- help Displays help for a command (?)
- list Lists commands
- dbal:import Import SQL file(s) directly to Database.
- dbal:run-sql Executes arbitrary SQL directly from the command line.
- orm:clear-cache:metadata Clear all metadata cache of the various cache drivers.
- orm:clear-cache:query Clear all query cache of the various cache drivers.
- orm:clear-cache:result Clear result cache of the various cache drivers.
- orm:convert-d1-schema Converts Doctrine 1.X schema into a Doctrine 2.X schema.
- orm:convert-mapping Convert mapping information between supported formats.
- orm:ensure-production-settings Verify that Doctrine is properly configured for a production environment.
- orm:generate-entities Generate entity classes and method stubs from your mapping information.
- orm:generate-proxies Generates proxy classes for entity classes.
- orm:generate-repositories Generate repository classes from your mapping information.
- orm:run-dql Executes arbitrary DQL directly from the command line.
- orm:schema-tool:create Processes the schema and either create it directly on EntityManager Storage Connection or generate the SQL output.
- orm:schema-tool:drop Processes the schema and either drop the database schema of EntityManager Storage Connection or generate the SQL output.
- orm:schema-tool:update Processes the schema and either update the database schema of EntityManager Storage Connection or generate the SQL output.

### For these commands are also available aliases:

- orm:convert:d1-schema is alias for orm:convert-d1-schema.
- orm:convert:mapping is alias for orm:convert-mapping.
- orm:generate:entities is alias for orm:generate-entities.
- orm:generate:proxies is alias for orm:generate-proxies.
- orm:generate:repositories is alias for orm:generate-repositories.
