<?php
declare(strict_types=1);

namespace Bungle\DingTalk\Tests\Export\ExcelWriter;

use Bungle\Framework\Export\ExcelWriter\ExcelOperator;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelOperatorTest extends MockeryTestCase
{
    public function test(): void
    {
        $book = new Spreadsheet();
        $cur = $book->getActiveSheet();
        $op = new ExcelOperator($book);
        self::assertSame($book, $op->getBook());
        self::assertSame($cur, $op->getSheet());

        self::assertEquals(1, $op->getRow());
        $op->setRow(100);
        self::assertEquals(100, $op->getRow());

        $op->nextRow();
        self::assertEquals(101, $op->getRow());

        $op->nextRow(10);
        self::assertEquals(111, $op->getRow());
    }
}
