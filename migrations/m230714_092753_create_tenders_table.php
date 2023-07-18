<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tenders`.
 */
class m230714_092753_create_tenders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tenders}}', [
            'id' => $this->primaryKey(),
            'tender_id' => $this->string(32)->notNull(),
            'description' => $this->text()->notNull(),
            'amount' => $this->decimal(14, 2)->notNull(),
            'date_modified' => $this->dateTime()->notNull()
        ]);

        $this->createIndex(
            'idx-tenders-tender-id',
            '{{%tenders}}',
            'tender_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-tenders-tender-id',
            '{{%tenders}}'
        );
        $this->dropTable('{{%tenders}}');
    }
}
