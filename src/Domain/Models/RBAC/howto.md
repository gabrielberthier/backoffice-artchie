# RBAC System

## What is RBAC

Role-based access control (RBAC) refers to the idea of assigning permissions to users based on their role within an organization. It offers a simple, manageable approach to access management that is less prone to error than assigning permissions to users individually.

When using RBAC for Role Management, you analyze the needs of your users and group them into roles based on common responsibilities. You then assign one or more roles to each user and one or more permissions to each role. The user-role and role-permissions relationships make it simple to perform user assignments since users no longer need to be managed individually, but instead have privileges that conform to the permissions assigned to their role(s).

For example, if you were using RBAC to control access for an HR application, you could give HR managers a role that allows them to update employee details, while other employees would be able to view only their own details.

When planning your access control strategy, it's best practice to assign users the fewest number of permissions that allow them to get their work done.

## Rules

All RBAC models must adhere to the following rules:

- Role assignment: a subject can only exercise privileges when the subject is assigned a role.
- Role authorization: the system must authorize a subject’s active role.
- Permission authorization: a subject can only apply permissions granted to the subject’s active role.

## The RBAC Model

There are three types of access control in the RBAC standard: core, hierarchical, and restrictive.

## Domain

A role is a collection of user privileges. Roles are different from traditional groups, which are collections of users. In the context of RBAC, permissions are not directly associated with identities but rather with roles. Roles are more reliable than groups because they are organized around access management. In a typical organization, features and activities change less frequently than identities.

## Idea

A subject (i.e, a person, system, routine) HAS one or more roles. Roles CANNOT be excludent. A subject wants to access a determined resource, but this resource MUST only be accessed under the circunstance of subject owning a set of permissions. A permission MAY have associated intent, such as CREATE, READ, UPDATE or DELETE. 

## Refs
https://frontegg.com/guides/rbac
https://auth0.com/docs/manage-users/access-control