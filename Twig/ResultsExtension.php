<?php

namespace Victoire\Widget\PollBundle\Twig;

use Doctrine\ORM\EntityRepository;
use Victoire\Widget\PollBundle\Entity\WidgetPoll;

class ResultsExtension extends \Twig_Extension_Core
{
    protected $pollRepo;

    /**
     * ResultsExtension constructor.
     *
     * @param EntityRepository $pollRepo
     */
    public function __construct(EntityRepository $pollRepo)
    {
        $this->pollRepo = $pollRepo;
    }

    /**
     * Register twig functions.
     *
     * @return \Twig_SimpleFunction[] The list of extensions
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('victoire_widget_poll_all_results', [$this, 'allResults'], ['is_safe' => ['html'],'needs_environment' => true]),
            new \Twig_SimpleFunction('victoire_widget_poll_single_result', [$this, 'singleResult'], ['is_safe' => ['html'],'needs_environment' => true]),
        ];
    }

    /**
     * Get extension name.
     *
     * @return string The name
     */
    public function getName()
    {
        return 'victoire_widget_poll_extension';
    }

    /**
     * Get all Polls results.
     *
     * @param \Twig_Environment $twig
     *
     * @return string
     */
    public function allResults(\Twig_Environment $twig)
    {
        $polls = $this->pollRepo->findAll();

        return $twig->render('VictoireWidgetPollBundle::results.html.twig', ['polls' => $polls]);
    }

    /**
     * Get a single Poll results.
     *
     * @param \Twig_Environment $twig
     *
     * @return string
     */
    public function singleResult(\Twig_Environment $twig, $widgetId)
    {
        $poll = $this->pollRepo->findOneById($widgetId);

        if(!$poll) {
            throw new \Exception('There is not any widget Poll with the id ['.$widgetId.'].');
        }

        return $twig->render('@VictoireWidgetPoll/includes/questionsResults.html.twig', ['questions' => $poll->getQuestions()]);
    }
}
