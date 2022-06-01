<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220601160643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A8D3DEE50');
        $this->addSql('DROP INDEX IDX_B3BA5A5A8D3DEE50 ON products');
        $this->addSql('ALTER TABLE products DROP stock_historic_id');
        $this->addSql('ALTER TABLE stock_historic ADD user_id_id INT NOT NULL, ADD product_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE stock_historic ADD CONSTRAINT FK_E294BB149D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE stock_historic ADD CONSTRAINT FK_E294BB14DE18E50B FOREIGN KEY (product_id_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_E294BB149D86650F ON stock_historic (user_id_id)');
        $this->addSql('CREATE INDEX IDX_E294BB14DE18E50B ON stock_historic (product_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products ADD stock_historic_id INT NOT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A8D3DEE50 FOREIGN KEY (stock_historic_id) REFERENCES stock_historic (id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A8D3DEE50 ON products (stock_historic_id)');
        $this->addSql('ALTER TABLE stock_historic DROP FOREIGN KEY FK_E294BB149D86650F');
        $this->addSql('ALTER TABLE stock_historic DROP FOREIGN KEY FK_E294BB14DE18E50B');
        $this->addSql('DROP INDEX IDX_E294BB149D86650F ON stock_historic');
        $this->addSql('DROP INDEX IDX_E294BB14DE18E50B ON stock_historic');
        $this->addSql('ALTER TABLE stock_historic DROP user_id_id, DROP product_id_id');
    }
}
