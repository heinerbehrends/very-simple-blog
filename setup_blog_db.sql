CREATE SCHEMA gorillablog;

USE gorillablog;

CREATE TABLE posts (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(256) NOT NULL,
  post TEXT NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE categories (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  category TEXT NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE articles_categories (
id INT NOT NULL AUTO_INCREMENT,
article_id INT NOT NULL,
category_id INT NOT NULL,
PRIMARY KEY (id),
FOREIGN KEY (article_id) REFERENCES articles(id),
FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE text_expand (
  id INT NOT NULL AUTO_INCREMENT,
  abbreviation TEXT NOT NULL,
  snippet TEXT NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE comments (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  comment TEXT NOT NULL,
  article_id INT NOT NULL,
  FOREIGN KEY (article_id) REFERENCES articles(id),
  PRIMARY KEY (id)
);
