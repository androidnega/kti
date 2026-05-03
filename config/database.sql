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
    faculty TEXT,
    slug TEXT,
    cover_image TEXT,
    detail_content TEXT,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Gallery / video rows for each program (created at runtime if missing)
CREATE TABLE IF NOT EXISTS program_media (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    program_id INTEGER NOT NULL,
    media_type TEXT NOT NULL DEFAULT 'image',
    file_path TEXT,
    external_url TEXT,
    caption TEXT,
    sort_order INTEGER NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE
);
CREATE INDEX IF NOT EXISTS idx_program_media_program ON program_media(program_id);

-- Insert initial admin user (credentials should be changed in production)
INSERT INTO users (name, email, password_hash, role) 
VALUES ('admin', 'admin', '$2y$12$nPafwFSxEX.vEtQ2O70Sj.25gWchaC/1aeWV//uyGofzNyxjhfNzC', 'admin');

-- Insert homepage with real information
INSERT INTO pages (slug, title, content) 
VALUES ('home', 'Welcome to Kikam Technical Institute', 'Founded in 1963, Kikam Technical Institute is a leading public TVET institution in Ghana, dedicated to providing demand-driven technical education relevant to the skilled manpower needs of our nation.');

-- Insert About page
INSERT INTO pages (slug, title, content) 
VALUES ('about', 'About Us', 'Kikam Technical Institute (KIMTECH) was established in 1963 as a public Technical and Vocational Education and Training (TVET) institution. Located in Kikam, Ellembelle District, Western Region of Ghana, we serve as both a boarding and day school with a mixed-gender student population. Our motto "Judge Us By Our Deeds" reflects our commitment to excellence in technical education.');

-- Insert Contact page
INSERT INTO pages (slug, title, content) 
VALUES ('contact', 'Contact Us', 'Kikam Technical Institute, P.O. Box 4, Kikam, Western Region, Ghana. Takoradi Elubo Road, Kikam. Email: Kimtechmail@yahoo.com or kikamtechinst@ges.gov.gh. Phone: +233 54 656 1424, +233 54 220 2670, +233 24 438 0894');

-- Insert department programs (faculty = site filter group; sync ims/ via tools/sync_ims_programs.php for photos)
INSERT INTO programs (name, department, faculty, slug, description) VALUES
('Electrical Engineering Technology', 'Electrical Engineering Technology', 'Engineering', 'electrical-engineering-technology', 'Hands-on training in electrical installations, motor control, power distribution, and workshop practice aligned to industry standards.'),
('Electronics Engineering', 'Electronics Engineering', 'Technology', 'electronics-engineering', 'Circuit design, electronic systems, troubleshooting, and maintenance of modern electronic equipment used in industry and everyday technology.'),
('Fashion', 'Fashion', 'General', 'fashion', 'Garment construction, pattern making, textiles, and fashion entrepreneurship for the creative and apparel sector.'),
('Mechanical Engineering', 'Mechanical Engineering', 'Engineering', 'mechanical-engineering', 'Mechanical systems, machining, thermodynamics, and manufacturing using modern workshop facilities.'),
('Plumbing and Gas Fitting', 'Plumbing and Gas Fitting', 'Construction', 'plumbing-and-gas-fitting', 'Safe installation and maintenance of plumbing, sanitation, and gas systems for residential and commercial buildings.'),
('Solar Technology', 'Solar Technology', 'Technology', 'solar-technology', 'Photovoltaic systems, renewable energy basics, site assessment, and practical solar installation skills.');
