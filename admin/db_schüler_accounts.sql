USE hsgg;

# Schüler accounts werden hier angelegt
ALTER TABLE hsgg.users
    ADD COLUMN teacher_id INT NULL
        AFTER role;

ALTER TABLE hsgg.users
    ADD COLUMN klasse VARCHAR(20)
        AFTER fachID;

SET @matheLehrerID = (SELECT id FROM hsgg.users WHERE username = 'MatheLehrer' LIMIT 1);
SET @deutschLehrerID = (SELECT id FROM hsgg.users WHERE username = 'DeutschLehrer' LIMIT 1);

INSERT INTO hsgg.users (username, password_hash, role, teacher_id, fachID, klasse)
VALUES
    (
        'schueler_6b_1',
        '$2y$12$2oOozMuI5v3WxzHfbZudGO6WBZ1jxPteLGiY5PTPIOqmTswuFfEB.',
        'schueler',
        @matheLehrerID,
        1,
        '6B'
    ),
    (
        'schueler_6b_2',
        '$2y$12$2oOozMuI5v3WxzHfbZudGO6WBZ1jxPteLGiY5PTPIOqmTswuFfEB.',
        'schueler',
        @matheLehrerID,
        1,
        '6B'
    ),
    (
        'schueler_6a_1',
        '$2y$12$2oOozMuI5v3WxzHfbZudGO6WBZ1jxPteLGiY5PTPIOqmTswuFfEB.',
        'schueler',
        @deutschLehrerID,
        2,
        '6A'
    );
