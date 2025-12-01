CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    company VARCHAR(100),
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    submit_time DATETIME
);