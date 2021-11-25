CREATE DATABASE IF NOT EXISTS taskforce
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE taskforce;

CREATE TABLE categories
(
  id    INT PRIMARY KEY AUTO_INCREMENT,
  name  VARCHAR(255) NOT NULL,
  alias VARCHAR(255) NOT NULL
);

CREATE TABLE cities
(
  id   INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL
);

CREATE TABLE users
(
  id                   INT PRIMARY KEY AUTO_INCREMENT,
  email                VARCHAR(255) UNIQUE                     NOT NULL,
  password             VARCHAR(255)                            NOT NULL,
  name                 VARCHAR(255)                            NOT NULL,
  birthdate            DATE                                    NULL,
  info                 TEXT                                    NULL,
  avatar               VARCHAR(255)                            NULL,
  rating               DECIMAL(3, 2) DEFAULT 0                 NOT NULL,
  city_id              INT                                     NOT NULL,
  phone                VARCHAR(255)                            NULL,
  skype                VARCHAR(255)                            NULL,
  telegram             VARCHAR(255)                            NULL,
  role                 VARCHAR(255)  DEFAULT 'customer'        NOT NULL,
  last_activity_at     DATETIME      DEFAULT CURRENT_TIMESTAMP NOT NULL,
  failed_tasks_count   INT           DEFAULT 0                 NOT NULL,
  notification_message BOOL          DEFAULT 0                 NOT NULL,
  notification_action  BOOL          DEFAULT 0                 NOT NULL,
  notification_review  BOOL          DEFAULT 0                 NOT NULL,
  show_only_customer   BOOL          DEFAULT 0                 NOT NULL,
  hide_profile         BOOL          DEFAULT 0                 NOT NULL,
  new_events           BOOL          DEFAULT 0                 NOT NULL,
  FOREIGN KEY (city_id) REFERENCES cities (id)
);

CREATE TABLE photos
(
  id      INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT          NOT NULL,
  path    VARCHAR(255) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE favorites
(
  id      INT PRIMARY KEY AUTO_INCREMENT,
  who_id  INT NOT NULL,
  whom_id INT NOT NULL,
  FOREIGN KEY (who_id) REFERENCES users (id),
  FOREIGN KEY (whom_id) REFERENCES users (id)
);


CREATE TABLE specializations
(
  id          INT PRIMARY KEY AUTO_INCREMENT,
  user_id     INT NOT NULL,
  category_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (category_id) REFERENCES categories (id)
);

CREATE TABLE tasks
(
  id          INT PRIMARY KEY AUTO_INCREMENT,
  customer_id INT                                    NOT NULL,
  executor_id INT                                    NULL,
  status      VARCHAR(255) DEFAULT 'new'             NOT NULL,
  title       VARCHAR(255)                           NOT NULL,
  description TEXT                                   NULL,
  category_id INT                                    NOT NULL,
  budget      INT                                    NULL,
  city_id     INT                                    NULL,
  coords_lat  VARCHAR(255)                           NULL,
  coords_lng  VARCHAR(255)                           NULL,
  created_at  DATETIME     DEFAULT CURRENT_TIMESTAMP NOT NULL,
  deadline_at DATETIME                               NULL,
  FOREIGN KEY (customer_id) REFERENCES users (id),
  FOREIGN KEY (executor_id) REFERENCES users (id),
  FOREIGN KEY (category_id) REFERENCES categories (id),
  FOREIGN KEY (city_id) REFERENCES cities (id)
);

CREATE TABLE files
(
  id       INT PRIMARY KEY AUTO_INCREMENT,
  task_id  INT          NOT NULL,
  filename VARCHAR(255) NOT NULL,
  path     VARCHAR(255) NOT NULL,
  FOREIGN KEY (task_id) REFERENCES tasks (id)
);

CREATE TABLE responses
(
  id          INT PRIMARY KEY AUTO_INCREMENT,
  task_id     INT            NOT NULL,
  executor_id INT            NOT NULL,
  comment     TEXT           NULL,
  budget      INT            NULL,
  is_refused  BOOL DEFAULT 0 NOT NULL,
  FOREIGN KEY (task_id) REFERENCES tasks (id),
  FOREIGN KEY (executor_id) REFERENCES users (id)
);

CREATE TABLE reviews
(
  id      INT PRIMARY KEY AUTO_INCREMENT,
  task_id INT        NOT NULL,
  rate    TINYINT(5) NOT NULL,
  comment TEXT       NULL,
  FOREIGN KEY (task_id) REFERENCES tasks (id)
);

CREATE TABLE events
(
  id      INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT          NOT NULL,
  task_id INT          NOT NULL,
  type    VARCHAR(255) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (task_id) REFERENCES tasks (id)
);

CREATE TABLE messages
(
  id           INT PRIMARY KEY AUTO_INCREMENT,
  task_id      INT                                NOT NULL,
  sender_id    INT                                NOT NULL,
  recipient_id INT                                NOT NULL,
  message      TEXT                               NOT NULL,
  created_at   DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  FOREIGN KEY (task_id) REFERENCES tasks (id),
  FOREIGN KEY (sender_id) REFERENCES users (id),
  FOREIGN KEY (recipient_id) REFERENCES users (id)
)


