<?php

require_once __DIR__ . '/../src/initialize.php';

class DemoModel extends \Modules\Model\Model
{

    /**
     * @return string Table/collection name
     */
    public function table()
    {

        return 'demo';
    }

    /**
     *
     * List of all available fields (columns)
     *
     * @return array
     */
    public function fields()
    {

        return ['id', 'title', 'content'];
    }
}

class InvalidDemoModel extends \Modules\Model\Model
{
    /**
     * @return string Table/collection name
     */
    public function table()
    {

        return '';
    }

    /**
     *
     * List of all available fields (columns)
     *
     * @return array
     */
    public function fields()
    {

        return [];
    }
}

class DemoTypedDecorator implements \Modules\Decorator\ITypedDecorator
{

    /** @var DemoModel */
    private $_model;

    /**
     *
     * Sets object that will be decorated
     *
     * @param $object
     * @return void
     */
    public function decorate($object)
    {

        $this->_model = $object;
    }

    public function pretty()
    {

        $title = $this->_model->getAttribute('title', 'No Title');
        $content = $this->_model->getAttribute('content', 'No Content');

        return "<h1>{$title}</h1><p>{$content}</p>NOTE: This content is decorated :)";
    }

    /**
     *
     * Retrieve model class name
     *
     * @return string
     */
    public function getObjectClass()
    {

        return DemoModel::class;
    }
}

/** @var DemoModel $demoModel */
$demoModel = DemoModel::getNewInstance(['id' => 1, 'title' => 'Decorator Example', 'content' => 'Read veryyy verryyyy carefully']);

/** @var DemoTypedDecorator $demoModelDecorator */
$demoModelDecorator = $demoModel->decorate(DemoTypedDecorator::class);

var_dump($demoModelDecorator->pretty());

/** @var InvalidDemoModel $invalidDemoModel This will be used to intentionally cause exception */
$invalidDemoModel = InvalidDemoModel::getNewInstance([]);

try {

    $invalidDemoModel->decorate(DemoTypedDecorator::class);
} catch (\Modules\Exception\Exceptions\DecoratorException $exception) {

    var_dump("Exception caused intentionally [{$exception->getCode()}]: {$exception->getMessage()}");
}


class GenericDecorator implements \Modules\Decorator\IDecorator
{

    private $_message;

    /**
     *
     * Sets object that will be decorated
     *
     * @param $object
     * @return void
     */
    public function decorate($object)
    {

        if (property_exists($object, 'message')) {

            $this->_message = "You got new message: {$object->message}";

            return;
        }

        $this->_message = "You have no new messages";
    }

    public function getMessage()
    {

        return $this->_message;
    }
}

$withMessage = new stdClass();
$withMessage->message = 'Hi there :)';

/** @var GenericDecorator $messageDecorator */
$messageDecorator = \Modules\Decorator\Decorator::decorate(GenericDecorator::class, $withMessage);

/** @var GenericDecorator $noMessageDecorator */
$noMessageDecorator = \Modules\Decorator\Decorator::decorate(GenericDecorator::class, new stdClass());

var_dump(
    $messageDecorator->getMessage(),
    $noMessageDecorator->getMessage()
);

