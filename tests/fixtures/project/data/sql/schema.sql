CREATE TABLE blog_post (id BIGINT AUTO_INCREMENT, title VARCHAR(255), teaser LONGTEXT, content_id BIGINT, INDEX content_id_idx (content_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE menu_item_translation (id BIGINT, label VARCHAR(255), lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE menu_item (id BIGINT AUTO_INCREMENT, site_id BIGINT NOT NULL, content_type_id BIGINT, content_id BIGINT, name VARCHAR(255) NOT NULL, custom_path VARCHAR(255), is_content_type_list TINYINT(1) DEFAULT '0', requires_auth TINYINT(1), requires_no_auth TINYINT(1), is_primary TINYINT(1), is_published TINYINT(1), date_published DATETIME, slug VARCHAR(255), root_id INT, lft INT, rgt INT, level SMALLINT, UNIQUE INDEX sluggable_idx (slug), INDEX content_id_idx (content_id), INDEX site_id_idx (site_id), INDEX content_type_id_idx (content_type_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE menu_item_group (menu_item_id BIGINT, group_id BIGINT, PRIMARY KEY(menu_item_id, group_id)) ENGINE = INNODB;
CREATE TABLE menu_item_permission (menu_item_id BIGINT, permission_id BIGINT, PRIMARY KEY(menu_item_id, permission_id)) ENGINE = INNODB;
CREATE TABLE page (id BIGINT AUTO_INCREMENT, title VARCHAR(255) NOT NULL, disable_comments TINYINT(1), content_id BIGINT, INDEX content_id_idx (content_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE content (id BIGINT AUTO_INCREMENT, site_id BIGINT NOT NULL, content_type_id BIGINT NOT NULL, content_template_id BIGINT, master_menu_item_id BIGINT, last_updated_by BIGINT, created_by BIGINT, locked_by BIGINT, is_published TINYINT(1), date_published DATETIME, custom_path VARCHAR(255), layout VARCHAR(255), slug VARCHAR(255), created_at DATETIME, updated_at DATETIME, INDEX master_menu_item_id_idx (master_menu_item_id), INDEX last_updated_by_idx (last_updated_by), INDEX created_by_idx (created_by), INDEX locked_by_idx (locked_by), INDEX site_id_idx (site_id), INDEX content_type_id_idx (content_type_id), INDEX content_template_id_idx (content_template_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE content_group (content_id BIGINT, group_id BIGINT, PRIMARY KEY(content_id, group_id)) ENGINE = INNODB;
CREATE TABLE content_permission (content_id BIGINT, permission_id BIGINT, PRIMARY KEY(content_id, permission_id)) ENGINE = INNODB;
CREATE TABLE content_slot_translation (id BIGINT, value LONGTEXT, lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE content_slot (id BIGINT AUTO_INCREMENT, content_id BIGINT NOT NULL, content_slot_type_id BIGINT NOT NULL, is_column TINYINT(1) DEFAULT '0', render_function VARCHAR(255), name VARCHAR(255) NOT NULL, INDEX content_id_idx (content_id), INDEX content_slot_type_id_idx (content_slot_type_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE content_slot_type (id BIGINT AUTO_INCREMENT, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE content_template (id BIGINT AUTO_INCREMENT, name VARCHAR(255) NOT NULL, type VARCHAR(255), content_type_id BIGINT, partial_path VARCHAR(255), component_path VARCHAR(255), body LONGTEXT, INDEX content_type_id_idx (content_type_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE content_type (id BIGINT AUTO_INCREMENT, name VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, plugin_name VARCHAR(255), list_path VARCHAR(255), view_path VARCHAR(255), layout VARCHAR(255), slug VARCHAR(255), UNIQUE INDEX sluggable_idx (slug), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE site (id BIGINT AUTO_INCREMENT, layout VARCHAR(255), title VARCHAR(255), description LONGTEXT, slug VARCHAR(255), UNIQUE INDEX sluggable_idx (slug), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE forgot_password (id BIGINT AUTO_INCREMENT, user_id BIGINT NOT NULL, unique_key VARCHAR(255), expires_at DATETIME NOT NULL, INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE groups (id BIGINT AUTO_INCREMENT, name VARCHAR(255) UNIQUE, description TEXT, created_at DATETIME, updated_at DATETIME, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE group_permission (group_id BIGINT, permission_id BIGINT, created_at DATETIME, updated_at DATETIME, PRIMARY KEY(group_id, permission_id)) ENGINE = INNODB;
CREATE TABLE permission (id BIGINT AUTO_INCREMENT, name VARCHAR(255) UNIQUE, description TEXT, created_at DATETIME, updated_at DATETIME, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE remember_key (user_id BIGINT, remember_key VARCHAR(32), ip_address VARCHAR(50), created_at DATETIME, updated_at DATETIME, INDEX user_id_idx (user_id), PRIMARY KEY(ip_address)) ENGINE = INNODB;
CREATE TABLE user (id BIGINT AUTO_INCREMENT, first_name VARCHAR(255), last_name VARCHAR(255), email_address VARCHAR(255) NOT NULL UNIQUE, username VARCHAR(128) NOT NULL UNIQUE, algorithm VARCHAR(128) DEFAULT 'sha1' NOT NULL, salt VARCHAR(128), password VARCHAR(128), is_active TINYINT(1) DEFAULT '1', is_super_admin TINYINT(1) DEFAULT '0', last_login DATETIME, created_at DATETIME, updated_at DATETIME, INDEX is_active_idx_idx (is_active), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE user_group (user_id BIGINT, group_id BIGINT, created_at DATETIME, updated_at DATETIME, PRIMARY KEY(user_id, group_id)) ENGINE = INNODB;
CREATE TABLE user_permission (user_id BIGINT, permission_id BIGINT, created_at DATETIME, updated_at DATETIME, PRIMARY KEY(user_id, permission_id)) ENGINE = INNODB;
ALTER TABLE blog_post ADD FOREIGN KEY (content_id) REFERENCES content(id) ON DELETE CASCADE;
ALTER TABLE menu_item_translation ADD FOREIGN KEY (id) REFERENCES menu_item(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE menu_item ADD FOREIGN KEY (site_id) REFERENCES site(id) ON DELETE CASCADE;
ALTER TABLE menu_item ADD FOREIGN KEY (content_type_id) REFERENCES content_type(id) ON DELETE CASCADE;
ALTER TABLE menu_item ADD FOREIGN KEY (content_id) REFERENCES content(id);
ALTER TABLE menu_item_group ADD FOREIGN KEY (menu_item_id) REFERENCES menu_item(id) ON DELETE CASCADE;
ALTER TABLE menu_item_group ADD FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE;
ALTER TABLE menu_item_permission ADD FOREIGN KEY (permission_id) REFERENCES permission(id) ON DELETE CASCADE;
ALTER TABLE menu_item_permission ADD FOREIGN KEY (menu_item_id) REFERENCES menu_item(id) ON DELETE CASCADE;
ALTER TABLE page ADD FOREIGN KEY (content_id) REFERENCES content(id) ON DELETE CASCADE;
ALTER TABLE content ADD FOREIGN KEY (site_id) REFERENCES site(id) ON DELETE CASCADE;
ALTER TABLE content ADD FOREIGN KEY (master_menu_item_id) REFERENCES menu_item(id) ON DELETE CASCADE;
ALTER TABLE content ADD FOREIGN KEY (locked_by) REFERENCES user(id) ON DELETE SET NULL;
ALTER TABLE content ADD FOREIGN KEY (last_updated_by) REFERENCES user(id) ON DELETE SET NULL;
ALTER TABLE content ADD FOREIGN KEY (created_by) REFERENCES user(id) ON DELETE SET NULL;
ALTER TABLE content ADD FOREIGN KEY (content_type_id) REFERENCES content_type(id) ON DELETE CASCADE;
ALTER TABLE content ADD FOREIGN KEY (content_template_id) REFERENCES content_template(id) ON DELETE SET NULL;
ALTER TABLE content_group ADD FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE;
ALTER TABLE content_group ADD FOREIGN KEY (content_id) REFERENCES content(id) ON DELETE CASCADE;
ALTER TABLE content_permission ADD FOREIGN KEY (permission_id) REFERENCES permission(id) ON DELETE CASCADE;
ALTER TABLE content_permission ADD FOREIGN KEY (content_id) REFERENCES content(id) ON DELETE CASCADE;
ALTER TABLE content_slot_translation ADD FOREIGN KEY (id) REFERENCES content_slot(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE content_slot ADD FOREIGN KEY (content_slot_type_id) REFERENCES content_slot_type(id) ON DELETE CASCADE;
ALTER TABLE content_slot ADD FOREIGN KEY (content_id) REFERENCES content(id) ON DELETE CASCADE;
ALTER TABLE content_template ADD FOREIGN KEY (content_type_id) REFERENCES content_type(id);
ALTER TABLE forgot_password ADD FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE;
ALTER TABLE group_permission ADD FOREIGN KEY (permission_id) REFERENCES permission(id) ON DELETE CASCADE;
ALTER TABLE group_permission ADD FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE;
ALTER TABLE remember_key ADD FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE;
ALTER TABLE user_group ADD FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE;
ALTER TABLE user_group ADD FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE;
ALTER TABLE user_permission ADD FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE;
ALTER TABLE user_permission ADD FOREIGN KEY (permission_id) REFERENCES permission(id) ON DELETE CASCADE;
