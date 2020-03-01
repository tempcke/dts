DROP TABLE IF EXISTS compiled_template;
CREATE TABLE compiled_template
(
    template_id     CHAR(36)      NOT NULL   COMMENT 'FOREIGN KEY',
    body            MEDIUMTEXT    NOT NULL   COMMENT 'the template compiled with helpers and partials',
    created_at      DATETIME      NOT NULL   DEFAULT NOW() COMMENT 'UTC datetime',

    UNIQUE KEY (template_id),
    FOREIGN KEY (template_id) REFERENCES template (template_id) ON DELETE CASCADE
) COMMENT '';