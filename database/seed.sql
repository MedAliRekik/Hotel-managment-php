USE hotel_management;

INSERT INTO users (fullname, email, password)
VALUES (
    'Admin User',
    'admin@hotel.com',
    '$2y$12$0P12.GJWQd8TSGgf32KTQ.THHqoLfbOVKRWgNdgh7KzIYOwWv1iwG'
);

INSERT INTO clients (first_name, last_name, phone, email, password) VALUES
('John', 'Doe', '+1-555-1000', 'john.doe@mail.com', NULL),
('Jane', 'Smith', '+1-555-2000', 'jane.smith@mail.com', NULL);

INSERT INTO rooms (room_number, type, price, status, image) VALUES
('101', 'Single', 80.00, 'available', NULL),
('102', 'Double', 120.00, 'available', NULL),
('201', 'Suite', 250.00, 'maintenance', NULL);

INSERT INTO reservations (client_id, room_id, check_in, check_out, status) VALUES
(1, 1, '2026-04-25', '2026-04-28', 'confirmed'),
(2, 2, '2026-05-01', '2026-05-04', 'pending');
