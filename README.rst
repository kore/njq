To make use of multiple cores for some rather long processing operations I
needed a way to fork multiple workers from a single PHP script multiple times
lately. So I created this small project which implements this in a way, so that
it should reusable by anybody.

Native job queue
================

The implementation and usage is pretty simple. There is in an interface
``JobProvider`` which has the methods ``hasJobs()`` and ``getNextJob()``, which
you need to implement. The second method should return valid PHP callbacks
- for example Closures. Those are then executed (in parallel) by the executor.

I also implemented a ``ShellJobProvider`` (which implements ``JobProvider``),
which is constructed from an array of shell commands, which then are executed
in parallel. A simple working example::

    <?php

    require 'njq/environment.php';

    $executor = new \njq\Executor();
    $executor->run(
        new \njq\ShellJobProvider( array(
            'echo 1 >> test',
            'echo 2 >> test',
            'echo 3 >> test',
            'echo 4 >> test',
            'echo 5 >> test',
        ) ),
        4
    );

    ?>

The file ``test`` will then contain something like (the order might vary)::

    5
    4
    3
    2
    1

The ``4`` (second parameter of ``\njq\Executor::run``) defines the number of
parallel processes to spawn. This should not exceed the number of available
cores in the most cases.

Logger
======

To view the state of the executor you can specify a logger, which then can echo
the current progress. A logger needs to implement the ``\njq\Logger`` interface
and is passed to the constructor of the executor. If no logger is specified a
blind dummy logger will be used. To use the shell logger, echoing the state to
STDERR, use::

    <?php

    require 'njq/environment.php';

    $executor = new \njq\Executor( new \njq\ShellLogger() );
    $executor->run(
        new \njq\ShellJobProvider( array(
            // ...
        ) ),
        4
    );

It will then print a status like the following, if the ``JobProvider``
implements the ``Countable`` interface::

      56 / 5880 (0.95%) |

If the ``JobProvider`` does not implement the ``Countable`` interface the
percent indicator obviously cannot be displayed.

Requirements
============

The job queue requires PHP 5.3 and the PHP PCNTL extension.

