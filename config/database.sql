-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    role TEXT DEFAULT 'admin',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Pages Table
CREATE TABLE IF NOT EXISTS pages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    slug TEXT UNIQUE NOT NULL,
    title TEXT NOT NULL,
    content TEXT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Sections Table
CREATE TABLE IF NOT EXISTS sections (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    page_id INTEGER NOT NULL,
    section_title TEXT NOT NULL,
    section_content TEXT,
    position INTEGER DEFAULT 0,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
);

-- Staff Table
CREATE TABLE IF NOT EXISTS staff (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    department TEXT,
    role TEXT,
    rank TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Programs Table
CREATE TABLE IF NOT EXISTS programs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    department TEXT,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO users (name, email, password_hash, role) 
VALUES ('Administrator', 'admin@kti.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert homepage with real information
INSERT INTO pages (slug, title, content) 
VALUES ('home', 'Welcome to Kikam Technical Institute', 'Founded in 1963, Kikam Technical Institute is a leading public TVET institution in Ghana, dedicated to providing demand-driven technical education relevant to the skilled manpower needs of our nation.');

-- Insert About page
INSERT INTO pages (slug, title, content) 
VALUES ('about', 'About Us', 'Kikam Technical Institute (KIMTECH) was established in 1963 as a public Technical and Vocational Education and Training (TVET) institution. Located in Kikam, Ellembelle District, Western Region of Ghana, we serve as both a boarding and day school with a mixed-gender student population. Our motto "Judge Us By Our Deeds" reflects our commitment to excellence in technical education.');

-- Insert Contact page
INSERT INTO pages (slug, title, content) 
VALUES ('contact', 'Contact Us', 'Kikam Technical Institute, P.O. Box 4, Kikam, Western Region, Ghana. Takoradi Elubo Road, Kikam. Email: Kimtechmail@yahoo.com or kikamtechinst@ges.gov.gh. Phone: +233 54 656 1424, +233 54 220 2670, +233 24 438 0894');

-- Insert real programs offered at Kikam Technical Institute
INSERT INTO programs (name, department, description) 
VALUES 
('Mechanical Engineering', 'Engineering', 'Study of mechanical systems, thermodynamics, and manufacturing. Equipped with modern machinery through the Oil and Gas Capacity Building Project.'),
('Welding and Fabrication', 'Engineering', 'Comprehensive training in welding techniques, metal fabrication, and industrial applications with state-of-the-art equipment.'),
('Electrical Engineering', 'Engineering', 'Focus on circuit design, power systems, and electronics. Hands-on training with modern electrical equipment and systems.'),
('Building Construction', 'Construction', 'Practical training in construction techniques, building design, and project management for the construction industry.'),
('Plumbing and Gas Fitting', 'Construction', 'Specialized training in plumbing systems, gas fitting, and installation techniques for residential and commercial applications.'),
('Carpentry and Joinery', 'Construction', 'Comprehensive woodworking program covering carpentry, joinery, furniture making, and wood finishing techniques.'),
('Auto Mechanics', 'Automotive', 'Complete automotive training covering engine repair, diagnostics, maintenance, and modern vehicle systems.'),
('Electronics', 'Technology', 'Training in electronic systems, circuit design, repair, and maintenance of electronic devices and equipment.'),
('General Technical', 'General', 'Broad-based technical education providing foundational skills across multiple technical disciplines.');
