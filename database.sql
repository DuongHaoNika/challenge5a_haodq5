create database challenge5a;
use challenge5a;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100),
  phone VARCHAR(20),
  role ENUM('teacher', 'student') NOT NULL,
  avatar VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sender_id INT NOT NULL,
  receiver_id INT NOT NULL,
  content TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE assignments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  teacher_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  file_path VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  deadline DATETIME,
  FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE submissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  assignment_id INT NOT NULL,
  student_id INT NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (assignment_id) REFERENCES assignments(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE challenges (
  id INT AUTO_INCREMENT PRIMARY KEY,
  teacher_id INT NOT NULL,
  challenge_hint TEXT,         
  file_path VARCHAR(255) NOT NULL, 
  file_content TEXT,           
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE challenge_attempts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  challenge_id INT NOT NULL,
  student_id INT NOT NULL,
  submitted_answer VARCHAR(255) NOT NULL,
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  is_correct BOOLEAN,  
  FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (username, password, full_name, email, phone, role, avatar) VALUES 
('teacher1', '$2y$10$ExIrGI3q9jPoj.TFO2V6MOTuwLMqC67DhGesU/nLWS6xIps/.CWlm', 'Teacher One', 'teacher1@example.com', '0123456789', 'teacher', NULL),
('teacher2', '$2y$10$ExIrGI3q9jPoj.TFO2V6MOTuwLMqC67DhGesU/nLWS6xIps/.CWlm', 'Teacher Two', 'teacher2@example.com', '0123456788', 'teacher', NULL),
('teacher3', '$2y$10$ExIrGI3q9jPoj.TFO2V6MOTuwLMqC67DhGesU/nLWS6xIps/.CWlm', 'Teacher Three', 'teacher3@example.com', '0123456787', 'teacher', NULL),
('teacher4', '$2y$10$ExIrGI3q9jPoj.TFO2V6MOTuwLMqC67DhGesU/nLWS6xIps/.CWlm', 'Teacher Four', 'teacher4@example.com', '0123456786', 'teacher', NULL),
('teacher5', '$2y$10$ExIrGI3q9jPoj.TFO2V6MOTuwLMqC67DhGesU/nLWS6xIps/.CWlm', 'Teacher Five', 'teacher5@example.com', '0123456785', 'teacher', NULL),
('student1', '$2y$10$ExIrGI3q9jPoj.TFO2V6MOTuwLMqC67DhGesU/nLWS6xIps/.CWlm', 'Student One', 'student1@example.com', '0987654321', 'student', NULL),
('student2', '$2y$10$ExIrGI3q9jPoj.TFO2V6MOTuwLMqC67DhGesU/nLWS6xIps/.CWlm', 'Student Two', 'student2@example.com', '0987654322', 'student', NULL),
('student3', '$2y$10$ExIrGI3q9jPoj.TFO2V6MOTuwLMqC67DhGesU/nLWS6xIps/.CWlm', 'Student Three', 'student3@example.com', '0987654323', 'student', NULL),
('student4', '$2y$10$ExIrGI3q9jPoj.TFO2V6MOTuwLMqC67DhGesU/nLWS6xIps/.CWlm', 'Student Four', 'student4@example.com', '0987654324', 'student', NULL),
('student5', '$2y$10$ExIrGI3q9jPoj.TFO2V6MOTuwLMqC67DhGesU/nLWS6xIps/.CWlm', 'Student Five', 'student5@example.com', '0987654325', 'student', NULL);

INSERT INTO messages (sender_id, receiver_id, content) VALUES
(1, 6, 'Hello, Student One!'),
(6, 1, 'Hello, Teacher One!'),
(2, 7, 'Hello, Student Two!'),
(7, 2, 'Hello, Teacher Two!'),
(3, 8, 'Hello, Student Three!'),
(8, 3, 'Hello, Teacher Three!'),
(4, 9, 'Hello, Student Four!'),
(9, 4, 'Hello, Teacher Four!'),
(5, 10, 'Hello, Student Five!'),
(10, 5, 'Hello, Teacher Five!');

-- INSERT INTO assignments (teacher_id, title, description, file_path, deadline) VALUES
-- (1, 'Assignment 1 by Teacher One', 'Description for Assignment 1', 'uploads/assignments/assignment1.pdf', '2025-04-01 23:59:59'),
-- (1, 'Assignment 2 by Teacher One', 'Description for Assignment 2', 'uploads/assignments/assignment2.pdf', '2025-04-05 23:59:59'),
-- (2, 'Assignment 1 by Teacher Two', 'Description for Assignment 3', 'uploads/assignments/assignment3.pdf', '2025-04-10 23:59:59'),
-- (2, 'Assignment 2 by Teacher Two', 'Description for Assignment 4', 'uploads/assignments/assignment4.pdf', '2025-04-15 23:59:59'),
-- (3, 'Assignment 1 by Teacher Three', 'Description for Assignment 5', 'uploads/assignments/assignment5.pdf', '2025-04-20 23:59:59'),
-- (3, 'Assignment 2 by Teacher Three', 'Description for Assignment 6', 'uploads/assignments/assignment6.pdf', '2025-04-25 23:59:59'),
-- (4, 'Assignment 1 by Teacher Four', 'Description for Assignment 7', 'uploads/assignments/assignment7.pdf', '2025-04-30 23:59:59'),
-- (4, 'Assignment 2 by Teacher Four', 'Description for Assignment 8', 'uploads/assignments/assignment8.pdf', '2025-05-05 23:59:59'),
-- (5, 'Assignment 1 by Teacher Five', 'Description for Assignment 9', 'uploads/assignments/assignment9.pdf', '2025-05-10 23:59:59'),
-- (5, 'Assignment 2 by Teacher Five', 'Description for Assignment 10', 'uploads/assignments/assignment10.pdf', '2025-05-15 23:59:59');

-- INSERT INTO submissions (assignment_id, student_id, file_path) VALUES
-- (1, 6, 'uploads/submissions/submission1.pdf'),
-- (2, 7, 'uploads/submissions/submission2.pdf'),
-- (3, 8, 'uploads/submissions/submission3.pdf'),
-- (4, 9, 'uploads/submissions/submission4.pdf'),
-- (5, 10, 'uploads/submissions/submission5.pdf'),
-- (6, 6, 'uploads/submissions/submission6.pdf'),
-- (7, 7, 'uploads/submissions/submission7.pdf'),
-- (8, 8, 'uploads/submissions/submission8.pdf'),
-- (9, 9, 'uploads/submissions/submission9.pdf'),
-- (10, 10, 'uploads/submissions/submission10.pdf');


