<?php

namespace Opengraph\Tests\Units;

require_once __DIR__ . '/../../src/Opengraph/Test/Unit.php';
require_once __DIR__ . '/../../src/Opengraph/Meta.php';
require_once __DIR__ . '/../../src/Opengraph/Opengraph.php';
require_once __DIR__ . '/../../src/Opengraph/Writer.php';

use Opengraph;

class Writer extends Opengraph\Test\Unit
{
    public function testClass()
    {
        $this->assert->testedClass
    		->isSubClassOf('\Opengraph\Opengraph')
    		->hasInterface('\Iterator')
    		->hasInterface('\Serializable')
    		->hasInterface('\Countable');
    }

    public function testWriter()
    {
        $writer = new Opengraph\Writer();
        $writer->append(Opengraph\Writer::OG_TITLE, 'test');

        $this->assert->string($writer->render())
            ->isEqualTo("\t" . '<meta property="og:title" content="test" />' . PHP_EOL);

        $this->assert->object($writer->addMeta(
                Opengraph\Writer::OG_TYPE,
                Opengraph\Writer::TYPE_WEBSITE,
                Opengraph\Writer::APPEND
            ))
            ->isInstanceOf('\Opengraph\Writer');

        $this->assert->object($writer->append(Opengraph\Writer::OG_TYPE, Opengraph\Writer::TYPE_WEBSITE))
            ->isInstanceOf('\Opengraph\Writer');

        $this->assert->object($writer->prepend(Opengraph\Writer::OG_IMAGE, 'http://www.google.com/'))
            ->isInstanceOf('\Opengraph\Writer');

        $this->assert->object($writer->getMetas())
            ->isInstanceOf('\ArrayObject');

        $this->assert->string(md5($writer->serialize()))
            ->isEqualTo('34335afeb44c2f54d5e28fa1a499e0b9');

        $this->assert->integer($writer->count())
            ->isEqualTo(3);

        $this->assert->object($writer->current())
            ->isInstanceOf('\Opengraph\Meta');

        $this->assert->integer($writer->key())
            ->isEqualTo(0);

        $writer->next();

        $this->assert->integer($writer->key())
            ->isEqualTo(1);

        $this->assert->boolean($writer->valid())
            ->isTrue();

        $writer->next();
        $writer->next();
        $writer->next();

        $this->assert->boolean($writer->valid())
            ->isFalse();

        $writer->rewind();

        $this->assert->integer($writer->key())
            ->isEqualTo(0);

        $writer->unserialize('C:11:"ArrayObject":751:{x:i:0;a:6:{i:0;O:14:"Opengraph\Meta":2:{s:12:" * _property";s:6:"og:url";s:11:" * _content";s:36:"http://www.imdb.com/title/tt0117500/";}i:1;O:14:"Opengraph\Meta":2:{s:12:" * _property";s:8:"og:title";s:11:" * _content";s:11:"Rock (1996)";}i:2;O:14:"Opengraph\Meta":2:{s:12:" * _property";s:7:"og:type";s:11:" * _content";s:11:"video.movie";}i:3;O:14:"Opengraph\Meta":2:{s:12:" * _property";s:8:"og:image";s:11:" * _content";s:99:"http://ia.media-imdb.com/images/M/MV5BMTM3MTczOTM1OF5BMl5BanBnXkFtZTYwMjc1NDA5._V1._SX98_SY140_.jpg";}i:4;O:14:"Opengraph\Meta":2:{s:12:" * _property";s:12:"og:site_name";s:11:" * _content";s:4:"IMDb";}i:5;O:14:"Opengraph\Meta":2:{s:12:" * _property";s:9:"fb:app_id";s:11:" * _content";s:15:"115109575169727";}};m:a:0:{}}');

        $this->assert->integer($writer->count())
            ->isEqualTo(6);

        $this->assert->array($writer->getArrayCopy())
            ->isEqualTo(array(
            'og:url' => 'http://www.imdb.com/title/tt0117500/',
            'og:title' => 'Rock (1996)',
            'og:type' => 'video.movie',
            'og:image' => array(
                0 => array(
                    'og:image:url' => 'http://ia.media-imdb.com/images/M/MV5BMTM3MTczOTM1OF5BMl5BanBnXkFtZTYwMjc1NDA5._V1._SX98_SY140_.jpg'
                )
            ),
            'og:site_name' => 'IMDb',
            'fb:app_id' => '115109575169727'
        ));

        $this->assert->string($writer->getMeta(Opengraph\Writer::OG_TITLE))->isEqualTo('Rock (1996)');

        /*$this->assert->object($writer->getMeta(Opengraph\Writer::OG_TITLE))
            ->isInstanceOf('\Opengraph\Meta');
        */
        $this->assert->boolean($writer->hasMeta(Opengraph\Writer::OG_TITLE))
        ->isTrue();

        $this->assert->boolean($writer->removeMeta(Opengraph\Writer::OG_TITLE))
            ->isTrue();

        $this->assert->integer($writer->count())
            ->isEqualTo(5);

        $this->assert->boolean($writer->removeMeta(Opengraph\Writer::OG_TITLE))
            ->isFalse();

        $this->assert->boolean($writer->getMeta(Opengraph\Writer::OG_TITLE))
            ->isFalse();

        $this->assert->boolean($writer->hasMeta(Opengraph\Writer::OG_TITLE))
            ->isFalse();

        $writer->addMeta(Opengraph\Writer::OG_TYPE, Opengraph\Writer::TYPE_BOOK, Opengraph\Writer::APPEND);
        $this->assert->string($writer->getMeta(Opengraph\Writer::OG_TYPE))->isEqualTo(Opengraph\Writer::TYPE_BOOK);

        $writer = new Opengraph\Writer();
        $this->assert->integer($writer->count())
            ->isEqualTo(5);

        $writer = new Opengraph\Writer();
        $writer->clear();

        $this->assert->integer($writer->count())
            ->isEqualTo(0);

        $writer->append(Opengraph\Writer::FB_ADMINS, 12345567657868);
        $this->assert->string($writer->render())
            ->isEqualTo("\t" . '<meta property="fb:admins" content="12345567657868" />' . PHP_EOL);

        $writer->clear();

        $writer->append(Opengraph\Writer::FB_ADMINS, '12345567657868,23334543656456');
        $this->assert->string($writer->render())
            ->isEqualTo("\t" . '<meta property="fb:admins" content="12345567657868,23334543656456" />' . PHP_EOL);
    }
}
