
CREATE TABLE IF NOT EXISTS jobs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    path TEXT,
    new_path TEXT,
    tags TEXT,
    is_processed TINYINT(1)
);
