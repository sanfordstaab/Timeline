<?php
// queries.php
// project specific queries processed by qworker.php

define('Q_NAME', 0);        // symbolic name for index
define('Q_QUERY', 1);       // query text to apply data to
define('Q_DATADESC', 2);    // description of data fields needed. Commas separate each description.  Used to check number of params given.

$aQueries = array(
    array('Q_SEL_COMMENT_IN_TEXT'                          , 'SELECT `commentId`, `text` FROM `comment` WHERE INSTR(text, "[0]")', 'searchText' ),
    array('Q_SEL_COMMENT_BY_KEYS'                          , 'SELECT `commentId`, `text` FROM `comment` WHERE `text` = "[0]"', 'keyText' ),
    array('Q_SEL_COMMENT'                                  , 'SELECT `commentId`, `text` FROM `comment` ORDER BY `text`', '' ),
    array('Q_SEL_COMMENT_IN_ID'                            , 'SELECT `commentId`, `text` FROM `comment` WHERE `commentId` = [0]', 'commentId' ),
    array('Q_INS_COMMENT'                                  , 'INSERT INTO `comment` (`text`) VALUES ("[0]")', 'newText' ),
    array('Q_UPD_COMMENT_IN_COMMENTID'                     , 'UPDATE `comment` SET `text` = "[0]" WHERE `commentId` = [1]' , 'newText, commentId' ),
    array('Q_DEL_COMMENT_IN_COMMENTID'                     , 'DELETE FROM `comment` WHERE `commentId` = [0]' , 'commentId' ),

    array('Q_SEL_BIBLE_BOOK'                               , 'SELECT `bookId`, `name` FROM `bibleBook`', '' ),
    array('Q_SEL_BIBLE_MONTH'                              , 'SELECT `bibleMonthId`, `name` FROM `bibleMonth`', '' ),
    array('Q_SEL_WHAT_TYPE'                                , 'SELECT `whatTypeId`, `desc` FROM `whatType` ORDER BY `desc`', '' ),
    array('Q_SEL_BIBLE_REF_IN_REFID'                       , 'SELECT `bibleRefId`, `bookId` ,`chapter` ,`verse` ,`text` FROM `bibleRef` WHERE `bibleRefId` = [0] ORDER BY `bookId`, `chapter`, `verse`', 'bookId' ),
    array('Q_SEL_BIBLE_REF'                                , 'SELECT `bibleRefId`, `bookId` ,`chapter` ,`verse` ,`text` FROM `bibleRef` ORDER BY `bookId`, `chapter`, `verse`', '' ),
    array('Q_SEL_BIBLE_REF_BY_KEYS'                        , 'SELECT `bibleRefId` FROM `bibleRef` WHERE `bookId` = [0] AND `chapter` = [1] AND `verse` = [2]', 'bookId, chapter, verse'),
    array('Q_SEL_BIBLE_REF_BY_KEYS_AND_VALUES'             , 'SELECT `bibleRefId` FROM `bibleRef` WHERE `bookId` = [0] AND `chapter` = [1] AND `verse` = [2] AND `text` = "[3]"', 'bookId, chapter, verse, text'),
    array('Q_DEL_BIBLE_REF'                                , 'DELETE FROM `bibleref` WHERE `bookId` = [0] AND `chapter` = [1] AND  `verse` = [2]', 'bookId, chapter, verse' ),
    array('Q_SEL_BIBLE_REF_ALL_IN_TEXT'                    , 'SELECT t1.bibleRefId as id, concat( t2.name, " ", t1.chapter, ":", t1.verse ) AS refText FROM `bibleRef` AS t1 LEFT JOIN `bibleBook` AS t2 ON t1.bookId = t2.bookId WHERE INSTR(t1.text, "[0]") ORDER BY t2.name, `chapter`, `verse`', 'searchText' ),
    array('Q_SEL_BIBLE_REF_ALL_IN_BOOK_AND_TEXT'           , 'SELECT t1.bibleRefId as id, concat( t2.name, " ", t1.chapter, ":", t1.verse ) AS refText FROM `bibleRef` AS t1 LEFT JOIN `bibleBook` AS t2 ON t1.bookId = t2.bookId WHERE t1.bookId = [0] AND INSTR(t1.text, "[1]") ORDER BY t2.name, `chapter`, `verse`' , 'bookId, searchText' ),
    array('Q_SEL_BIBLE_REF_ALL_IN_BOOK'                    , 'SELECT t1.bibleRefId as id, concat( t2.name, " ", t1.chapter, ":", t1.verse ) AS refText FROM `bibleRef` AS t1 LEFT JOIN `bibleBook` AS t2 ON t1.bookId = t2.bookId WHERE t1.bookId = [0] ORDER BY t2.name, `chapter`, `verse`', 'bookId' ),
    array('Q_INS_BIBLE_REF'                                , 'INSERT INTO `bibleRef`(`bookId`, `chapter`, `verse`, `text`) VALUES ([0],[1],[2],"[3]")', 'bookId, chapter, verse, text' ),
    array('Q_UPD_BIBLE_REF_TEXT_BY_BOOK_CHAPTER_AND_VERSE' , 'UPDATE `bibleRef` SET `text` = "[0]" WHERE `bookId`=[1] AND `chapter`=[2] AND `verse`=[3]', 'newText, bookId, chapter, verse' ),

    array('Q_SEL_WHERE_BY_KEYS'                            , 'SELECT `whereId` FROM `where` WHERE `text` = [0]', 'text' ),
    array('Q_SEL_WHATTYPE_COUNT_BY_TYPE'                   , 'SELECT COUNT(`whatId`) FROM `whattype` WHERE `desc` = [0]', 'desc' )
);

foreach ($aQueries as $k => $v) define($v[Q_NAME], $k);

require_once('qworker.php');
?>
