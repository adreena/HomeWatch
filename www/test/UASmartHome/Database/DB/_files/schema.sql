/*
 * This is the schema setup file we're going to use to setup test-cases.
 *
 * I basically just copied this from /etc/sql/create-users...
 *
 * TODO: Make sure that we have ONE copy of the schema, or
 * else, copying the schema is an automated task.
 */

CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(30) NOT NULL UNIQUE,
  password VARCHAR(64) NOT NULL,
  salt VARCHAR(3) NOT NULL,
  PRIMARY KEY(id)
);
