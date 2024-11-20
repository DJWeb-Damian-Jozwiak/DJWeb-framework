<?php

namespace ErrorHandling;

use DJWeb\Framework\Base\Application;
use DJWeb\Framework\Console\Output\Contacts\OutputContract;
use DJWeb\Framework\ErrorHandling\Renderers\ConsoleRenderer;
use DJWeb\Framework\ErrorHandling\Renderers\Helpers\DebugSession;
use DJWeb\Framework\ErrorHandling\Renderers\Partials\ConsoleCommandProcessor;
use DJWeb\Framework\ErrorHandling\Renderers\Partials\ConsoleRendererFactory;
use DJWeb\Framework\ErrorHandling\TraceCollection;
use RuntimeException;
use Tests\BaseTestCase;
use Tests\Helpers\TestSequenceCompletedException;

class ConsoleRendererTest extends BaseTestCase
{
    private OutputContract $output;
    private ConsoleRenderer $renderer;

    private array $commands = [];
    private int $currentCommand = 0;

    public function setUp(): void
    {
        parent::setUp();
        $this->output = $this->createMock(OutputContract::class);
        $this->output
            ->method('question')
            ->willReturnCallback(function () {
                if ($this->currentCommand >= count($this->commands)) {
                    throw new TestSequenceCompletedException();
                }
                return $this->commands[$this->currentCommand++];
            });

        $app = Application::getInstance();
        $app->set(OutputContract::class, $this->output);
        $this->renderer = new ConsoleRendererFactory($app)->create();
    }

    public function testRenderHelp()
    {
        $this->commands = ['help'];

        $this->output->expects($this->once())
            ->method('info')
            ->with('Available commands:');

        $expectedOutput = [];
        $this->output
            ->expects($this->exactly(10))
            ->method('writeln')
            ->willReturnCallback(function($message) use (&$expectedOutput) {
                $expectedOutput[] = $message;
            });

        $this->executeWithExceptionCatch(function () {
            $this->renderer->render(new RuntimeException('Test exception'));
        });

        $this->assertEquals([
            '',
            '<danger>Test exception</danger>',
            '',
            '  trace      Show full stack trace',
            '  frame N    Show details of frame N',
            '  vars N     Show local variables in frame N',
            '  source N   Show more source code for frame N',
            '  env        Show environment details',
            '  help       Show this help message',
            '  exit       Exit interactive debugger'
        ], $expectedOutput);
    }

    public function testRenderTrace()
    {
        $this->commands = ['trace'];
        $expectedOutput = [];
        $any = $this->any();
        $this->output
            ->expects($any)
            ->method('writeln')
            ->willReturnCallback(function($message) use (&$expectedOutput) {
                $expectedOutput[] = $message;
            });


        $this->executeWithExceptionCatch(function () {
            $this->renderer->render(new RuntimeException('Test exception'));
        });

        $this->assertTrue(in_array('#0 (__construct)', $expectedOutput));
    }

    public function testRenderFrame()
    {
        $this->commands = ['frame 0'];
        $expectedOutput = [];
        $any = $this->any();
        $this->output
            ->expects($any)
            ->method('writeln')
            ->willReturnCallback(function($message) use (&$expectedOutput) {
                $expectedOutput[] = $message;
            });


        $this->executeWithExceptionCatch(function () {
            $this->renderer->render(new RuntimeException('Test exception'));
        });


        $this->assertTrue(in_array('Call: __construct()', $expectedOutput));
    }

    public function testRenderInvalidFrame()
    {
        $this->commands = ['frame 1000'];
        $expectedOutput = [];
        $any = $this->any();
        $this->output
            ->expects($this->exactly(3))
            ->method('writeln')
            ->willReturnCallback(function($message) use (&$expectedOutput) {
                $expectedOutput[] = $message;
            });


        $this->executeWithExceptionCatch(function () {
            $this->renderer->render(new RuntimeException('Test exception'));
        });

    }


    public function testRenderSource()
    {
        $this->commands = ['source 0'];
        $expectedOutput = [];
        $any = $this->any();
        $this->output
            ->expects($any)
            ->method('writeln')
            ->willReturnCallback(function($message) use (&$expectedOutput) {
                $expectedOutput[] = $message;
            });


        $this->executeWithExceptionCatch(function () {
            $this->renderer->render(new RuntimeException('Test exception'));
        });

        $this->assertCount(44, $expectedOutput);
    }

    public function testRenderVars()
    {
        $this->commands = ['vars 0'];
        $expectedOutput = [];
        $any = $this->any();
        $this->output
            ->expects($any)
            ->method('writeln')
            ->willReturnCallback(function($message) use (&$expectedOutput) {
                $expectedOutput[] = $message;
            });


        $this->executeWithExceptionCatch(function () {
            $this->renderer->render(new RuntimeException('Test exception'));
        });


        $this->assertTrue(in_array('$this = null', $expectedOutput));
    }

    public function testRenderEnv()
    {
        $this->commands = ['env'];
        $expectedOutput = [];
        $any = $this->any();
        $this->output
            ->expects($any)
            ->method('writeln')
            ->willReturnCallback(function($message) use (&$expectedOutput) {
                $expectedOutput[] = $message;
            });


        $this->executeWithExceptionCatch(function () {
            $this->renderer->render(new RuntimeException('Test exception'));
        });


        $this->assertTrue(in_array('Server: CLI', $expectedOutput));
    }

    public function testRenderInvalidCommand()
    {
        $this->commands = ['missing'];
        $expectedOutput = [];
        $any = $this->any();
        $this->output
            ->expects($any)
            ->method('writeln')
            ->willReturnCallback(function($message) use (&$expectedOutput) {
                $expectedOutput[] = $message;
            });


        $this->executeWithExceptionCatch(function () {
            $this->renderer->render(new RuntimeException('Test exception'));
        });

        $this->assertCount(3, $expectedOutput);
    }

    public function testRenderExit()
    {
        $commands = ['help', 'frame 0', 'exit'];
        $currentCommand = 0;

        $output = $this->createMock(OutputContract::class);
        $output->method('question')
            ->willReturnCallback(function() use ($commands, &$currentCommand) {
                return $commands[$currentCommand++];
            });

        $session = new DebugSession(
            $output,
            $this->createMock(ConsoleCommandProcessor::class),
            $this->createMock(TraceCollection::class)
        );

        // Act
        $session->run();

        // Assert
        $this->assertEquals(3, $currentCommand);
    }

    private function executeWithExceptionCatch(callable $callback): void
    {
        try {
            $callback();
        } catch (TestSequenceCompletedException) {
            return;
        }

        $this->fail('Test sequence not completed');
    }
}