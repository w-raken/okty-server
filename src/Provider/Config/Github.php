<?php declare(strict_types=1);

namespace App\Provider\Config;

use Github\Api\Repo;
use Github\Client;
use Symfony\Component\Yaml\Yaml;

class Github implements ConfigProvider
{
    private $client;
    private $githubUser;
    private $githubRepo;
    private $githubBranch;
    private $containersPath;

    public function __construct(
        Client $client,
        string $githubUser,
        string $githubRepo,
        string $githubBranch,
        string $containersPath
    )
    {
        $this->client = $client;
        $this->githubUser = $githubUser;
        $this->githubRepo = $githubRepo;
        $this->githubBranch = $githubBranch;
        $this->containersPath = $containersPath;
    }

    public function getAllContainers(): array
    {
        /** @var array $containers */
        $containers = [];
        /** @var array $list */
        $list = $this->getTree($this->containersPath);

        foreach ($list as $data) {
            if (!isset($data['name'])) {
                continue;
            }

            $containers[] = $this->getContainer($data['name']);
        }

        return $containers;
    }

    public function getContainer(string $name): array
    {
        /** @var Repo $repo */
        $repo = $this->client->api('repo');
        $path = $this->containersPath . '/' . $name;

        /** @var array $data */
        $data = $repo->contents()->show($this->githubUser, $this->githubRepo, $path, $this->githubBranch);
        $content = base64_decode($data['content'] ?? '');

        return Yaml::parse($content, Yaml::PARSE_OBJECT);
    }

    private function getTree(string $path): array
    {
        /** @var Repo $repo */
        $repo = $this->client->api('repo');
        /** @var array $fileInfo */
        $fileInfo = $repo->contents()->show($this->githubUser, $this->githubRepo, $path, $this->githubBranch);

        return $fileInfo;
    }
}
