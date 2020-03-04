DROP TABLE IF EXISTS template;
CREATE TABLE template (
      template_id  CHAR(36)         NOT NULL   COMMENT 'UUID to identify a single unique version',
      doc_type     VARCHAR(255)     NOT NULL   COMMENT 'user provided document type, works as a category',
      template_key VARCHAR(255)     NOT NULL   COMMENT 'user provided constant',
      name         VARCHAR(255)                COMMENT 'user provided name',
      author       VARCHAR(255)                COMMENT 'user provided author',
      created_at   DATETIME         NOT NULL   COMMENT 'UTC datetime',
      body         MEDIUMTEXT       NOT NULL   COMMENT 'template body',
      PRIMARY KEY (template_id),
      INDEX doctype_template (doc_type, template_key)
) COMMENT 'document template versions, insert only, no update please';

DROP TABLE IF EXISTS docdata;
CREATE TABLE docdata (
     data_id      CHAR(36)         NOT NULL   COMMENT 'UUID to identify a single unique version',
     doc_type     VARCHAR(255)     NOT NULL   COMMENT 'user provided document type, works as a category',
     data_key     VARCHAR(255)     NOT NULL   COMMENT 'user provided constant',
     created_at   DATETIME         NOT NULL   COMMENT 'UTC datetime',
     data         JSON             NOT NULL   COMMENT 'json data used to render template',
     PRIMARY KEY (data_id),
     INDEX doctype_data (doc_type, data_key)
) COMMENT 'docdata versions used to render templates, insert only, no update please';

DROP TABLE IF EXISTS renderlog;
CREATE TABLE renderlog (
    request_id   INT(11) UNSIGNED NOT NULL   AUTO_INCREMENT,
    request      JSON             NOT NULL   COMMENT 'json request params containing some of doc_type, template_key, template_id, data_key, data_id',
    template_id  CHAR(36)         NOT NULL   COMMENT 'template used to render request',
    data_id      CHAR(36)         NOT NULL   COMMENT 'docdata used to render request',
    format       VARCHAR(32)      NOT NULL   COMMENT 'document format such as pdf or html',
    created_at   DATETIME         NOT NULL   COMMENT 'UTC datetime',
    PRIMARY KEY (request_id),
    FOREIGN KEY (template_id)     REFERENCES template(template_id),
    FOREIGN KEY (data_id)         REFERENCES docdata(data_id)
) COMMENT 'log of render requests';
