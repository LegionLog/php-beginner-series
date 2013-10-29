<?php

use KnpU\ActivityRunner\Assert\PhpAwareSuite;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractSuite extends PhpAwareSuite
{
    protected function assertPhpTagExists($code)
    {
        $this->assertContains('<?php', $code, 'Don\'t forget to write <?php before writing PHP code.');
    }

    protected function assertEchoExists($code)
    {
        $this->assertContains('echo', $code, 'I don\'t see your "echo" statement. Did you remember to write "echo"?');
    }

    /**
     * Returns the Crawler for the single h1 element
     *
     * @param $output
     * @return Crawler
     */
    protected function getCrawlerForSingleH1Element($output)
    {
        return $this->getCrawlerForSingleElement(
            $output,
            'h1',
            'I don\'t see your <h1> in the output - make sure you have <h1>Hello World</h1> in your final HTML',
            'I see more than 1 h1 tag - just create one <h1></h1> pair and print Hello World inside of it'
        );
    }

    /**
     * Returns the Crawler for the single h1 element
     *
     * @param string $output
     * @param string $cssSelector
     * @param string $zeroError
     * @param string $moreThanOneError
     * @return Crawler
     */
    protected function getCrawlerForSingleElement($output, $cssSelector, $zeroError, $moreThanOneError)
    {
        $crawler = $this->getCrawler($output);
        $ele = $crawler->filter($cssSelector);
        $this->assertNotEquals(0, count($ele), $zeroError);
        $this->assertEquals(1, count($ele), $moreThanOneError);

        return $ele;
    }

    protected function assertNodeContainsText(Crawler $node, $expectedText, $ignoreCase = true)
    {
        // 6) Look for Hello World in the output inside the h1
        $this->assertContains(
            $expectedText,
            $node->text(),
            sprintf('I see your <%s> tag, but it has the wrong text in it. I see "%s"', $node->current()->nodeName, $node->text()),
            $ignoreCase
        );
    }
}