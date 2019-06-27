<?php
// See https://gist.github.com/1809044
// Originally available from https://gist.github.com/nichtich/5290675#file-deploy-php

// ------------------------------------------------------------------------------------
// NOTE: For this to work, php-fpm user and group need to be set to the same user
// as was used to pull git in the first place.
// Typically on Amazon staging using centos 7, it'll be "centos"
// sudo vi /etc/php-fpm.d/www.conf
// Change "user" and "group" to "centos" (in this case)
// In git repo, check settings -> webhooks to confirm the hook ran under "Recent Deliveries"
// ------------------------------------------------------------------------------------

// FOR TESTING
// ob_start();
// echo $_SERVER['HTTP_X_HUB_SIGNATURE'];
// print_r(get_defined_vars());
// $contents = ob_get_flush();
// file_put_contents('deploy.txt',$contents);
// die;

// kill it if the "secret" set in the webhook isn't present
$postdata = file_get_contents('php://input');
$payload = json_decode($_POST['payload']);
if (!isset($_SERVER['HTTP_X_HUB_SIGNATURE']) || !hash_equals('sha1='.hash_hmac('sha1', $postdata, 'secret'),$_SERVER['HTTP_X_HUB_SIGNATURE'])){
	header('HTTP/1.1 403 Forbidden');
	exit;
}
// only deploy on master branch
if ($payload->ref != 'refs/heads/master') {
	echo 'No action. Only master branch can be pulled';
	exit;
}

$commands = array(
	'echo $PWD',
	'whoami',
	'git pull',
	'git status'
);
$output = "\n";
$log = "####### ".date('Y-m-d H:i:s'). " #######\n";
foreach($commands as $command){
	// Run it
	$tmp = shell_exec("$command 2>&1");
	// Output
	$output .= "$command \n";
	$output .= "------------------ \n";
	$output .= htmlentities(trim($tmp)) . "\n\n";
	$log  .= "\$ $command\n".trim($tmp)."\n";
}
$log .= "\n";
file_put_contents ('deploy-log.txt',$log,FILE_APPEND);
echo $output;






// ------------------------------------
// Example of GITHUB Payload
// ------------------------------------

// array (
//   'ref' => 'refs/heads/master',
//   'before' => 'ed4bb740b2821f3d37733ab6f30e57b3f9e8a367',
//   'after' => 'e0c4666d2db08fe58d632edcc84cd425e48334bc',
//   'created' => false,
//   'deleted' => false,
//   'forced' => false,
//   'base_ref' => NULL,
//   'compare' => 'https://github.com/industrialdev/ccua-website-wordpress/compare/ed4bb740b282...e0c4666d2db0',
//   'commits' =>
//   array (
//     0 =>
//     array (
//       'id' => 'e0c4666d2db08fe58d632edcc84cd425e48334bc',
//       'tree_id' => 'b9cecde7a89f62ef53592f7435edfb4402ce5ae5',
//       'distinct' => true,
//       'message' => 'Update README.md',
//       'timestamp' => '2019-05-28T09:07:47-04:00',
//       'url' => 'https://github.com/industrialdev/ccua-website-wordpress/commit/e0c4666d2db08fe58d632edcc84cd425e48334bc',
//       'author' =>
//       array (
//         'name' => 'rferguson10',
//         'email' => 'rferguson@industrialagency.ca',
//         'username' => 'rferguson10',
//       ),
//       'committer' =>
//       array (
//         'name' => 'GitHub',
//         'email' => 'noreply@github.com',
//         'username' => 'web-flow',
//       ),
//       'added' =>
//       array (
//       ),
//       'removed' =>
//       array (
//       ),
//       'modified' =>
//       array (
//         0 => 'README.md',
//       ),
//     ),
//   ),
//   'head_commit' =>
//   array (
//     'id' => 'e0c4666d2db08fe58d632edcc84cd425e48334bc',
//     'tree_id' => 'b9cecde7a89f62ef53592f7435edfb4402ce5ae5',
//     'distinct' => true,
//     'message' => 'Update README.md',
//     'timestamp' => '2019-05-28T09:07:47-04:00',
//     'url' => 'https://github.com/industrialdev/ccua-website-wordpress/commit/e0c4666d2db08fe58d632edcc84cd425e48334bc',
//     'author' =>
//     array (
//       'name' => 'rferguson10',
//       'email' => 'rferguson@industrialagency.ca',
//       'username' => 'rferguson10',
//     ),
//     'committer' =>
//     array (
//       'name' => 'GitHub',
//       'email' => 'noreply@github.com',
//       'username' => 'web-flow',
//     ),
//     'added' =>
//     array (
//     ),
//     'removed' =>
//     array (
//     ),
//     'modified' =>
//     array (
//       0 => 'README.md',
//     ),
//   ),
//   'repository' =>
//   array (
//     'id' => 178923814,
//     'node_id' => 'MDEwOlJlcG9zaXRvcnkxNzg5MjM4MTQ=',
//     'name' => 'ccua-website-wordpress',
//     'full_name' => 'industrialdev/ccua-website-wordpress',
//     'private' => true,
//     'owner' =>
//     array (
//       'name' => 'industrialdev',
//       'email' => 'devteam@industrialagency.ca',
//       'login' => 'industrialdev',
//       'id' => 16219206,
//       'node_id' => 'MDEyOk9yZ2FuaXphdGlvbjE2MjE5MjA2',
//       'avatar_url' => 'https://avatars0.githubusercontent.com/u/16219206?v=4',
//       'gravatar_id' => '',
//       'url' => 'https://api.github.com/users/industrialdev',
//       'html_url' => 'https://github.com/industrialdev',
//       'followers_url' => 'https://api.github.com/users/industrialdev/followers',
//       'following_url' => 'https://api.github.com/users/industrialdev/following{/other_user}',
//       'gists_url' => 'https://api.github.com/users/industrialdev/gists{/gist_id}',
//       'starred_url' => 'https://api.github.com/users/industrialdev/starred{/owner}{/repo}',
//       'subscriptions_url' => 'https://api.github.com/users/industrialdev/subscriptions',
//       'organizations_url' => 'https://api.github.com/users/industrialdev/orgs',
//       'repos_url' => 'https://api.github.com/users/industrialdev/repos',
//       'events_url' => 'https://api.github.com/users/industrialdev/events{/privacy}',
//       'received_events_url' => 'https://api.github.com/users/industrialdev/received_events',
//       'type' => 'Organization',
//       'site_admin' => false,
//     ),
//     'html_url' => 'https://github.com/industrialdev/ccua-website-wordpress',
//     'description' => NULL,
//     'fork' => false,
//     'url' => 'https://github.com/industrialdev/ccua-website-wordpress',
//     'forks_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/forks',
//     'keys_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/keys{/key_id}',
//     'collaborators_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/collaborators{/collaborator}',
//     'teams_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/teams',
//     'hooks_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/hooks',
//     'issue_events_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/issues/events{/number}',
//     'events_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/events',
//     'assignees_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/assignees{/user}',
//     'branches_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/branches{/branch}',
//     'tags_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/tags',
//     'blobs_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/git/blobs{/sha}',
//     'git_tags_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/git/tags{/sha}',
//     'git_refs_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/git/refs{/sha}',
//     'trees_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/git/trees{/sha}',
//     'statuses_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/statuses/{sha}',
//     'languages_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/languages',
//     'stargazers_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/stargazers',
//     'contributors_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/contributors',
//     'subscribers_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/subscribers',
//     'subscription_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/subscription',
//     'commits_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/commits{/sha}',
//     'git_commits_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/git/commits{/sha}',
//     'comments_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/comments{/number}',
//     'issue_comment_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/issues/comments{/number}',
//     'contents_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/contents/{+path}',
//     'compare_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/compare/{base}...{head}',
//     'merges_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/merges',
//     'archive_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/{archive_format}{/ref}',
//     'downloads_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/downloads',
//     'issues_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/issues{/number}',
//     'pulls_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/pulls{/number}',
//     'milestones_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/milestones{/number}',
//     'notifications_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/notifications{?since,all,participating}',
//     'labels_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/labels{/name}',
//     'releases_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/releases{/id}',
//     'deployments_url' => 'https://api.github.com/repos/industrialdev/ccua-website-wordpress/deployments',
//     'created_at' => 1554143651,
//     'updated_at' => '2019-05-28T13:00:13Z',
//     'pushed_at' => 1559048867,
//     'git_url' => 'git://github.com/industrialdev/ccua-website-wordpress.git',
//     'ssh_url' => 'git@github.com:industrialdev/ccua-website-wordpress.git',
//     'clone_url' => 'https://github.com/industrialdev/ccua-website-wordpress.git',
//     'svn_url' => 'https://github.com/industrialdev/ccua-website-wordpress',
//     'homepage' => NULL,
//     'size' => 16561,
//     'stargazers_count' => 0,
//     'watchers_count' => 0,
//     'language' => 'PHP',
//     'has_issues' => true,
//     'has_projects' => true,
//     'has_downloads' => true,
//     'has_wiki' => true,
//     'has_pages' => false,
//     'forks_count' => 0,
//     'mirror_url' => NULL,
//     'archived' => false,
//     'disabled' => false,
//     'open_issues_count' => 0,
//     'license' => NULL,
//     'forks' => 0,
//     'open_issues' => 0,
//     'watchers' => 0,
//     'default_branch' => 'master',
//     'stargazers' => 0,
//     'master_branch' => 'master',
//     'organization' => 'industrialdev',
//   ),
//   'pusher' =>
//   array (
//     'name' => 'rferguson10',
//     'email' => 'rferguson@industrialagency.ca',
//   ),
//   'organization' =>
//   array (
//     'login' => 'industrialdev',
//     'id' => 16219206,
//     'node_id' => 'MDEyOk9yZ2FuaXphdGlvbjE2MjE5MjA2',
//     'url' => 'https://api.github.com/orgs/industrialdev',
//     'repos_url' => 'https://api.github.com/orgs/industrialdev/repos',
//     'events_url' => 'https://api.github.com/orgs/industrialdev/events',
//     'hooks_url' => 'https://api.github.com/orgs/industrialdev/hooks',
//     'issues_url' => 'https://api.github.com/orgs/industrialdev/issues',
//     'members_url' => 'https://api.github.com/orgs/industrialdev/members{/member}',
//     'public_members_url' => 'https://api.github.com/orgs/industrialdev/public_members{/member}',
//     'avatar_url' => 'https://avatars0.githubusercontent.com/u/16219206?v=4',
//     'description' => 'Industrial is a web, mobile, and graphic design shop located in Westboro Village, Ottawa.',
//   ),
//   'sender' =>
//   array (
//     'login' => 'rferguson10',
//     'id' => 2422388,
//     'node_id' => 'MDQ6VXNlcjI0MjIzODg=',
//     'avatar_url' => 'https://avatars2.githubusercontent.com/u/2422388?v=4',
//     'gravatar_id' => '',
//     'url' => 'https://api.github.com/users/rferguson10',
//     'html_url' => 'https://github.com/rferguson10',
//     'followers_url' => 'https://api.github.com/users/rferguson10/followers',
//     'following_url' => 'https://api.github.com/users/rferguson10/following{/other_user}',
//     'gists_url' => 'https://api.github.com/users/rferguson10/gists{/gist_id}',
//     'starred_url' => 'https://api.github.com/users/rferguson10/starred{/owner}{/repo}',
//     'subscriptions_url' => 'https://api.github.com/users/rferguson10/subscriptions',
//     'organizations_url' => 'https://api.github.com/users/rferguson10/orgs',
//     'repos_url' => 'https://api.github.com/users/rferguson10/repos',
//     'events_url' => 'https://api.github.com/users/rferguson10/events{/privacy}',
//     'received_events_url' => 'https://api.github.com/users/rferguson10/received_events',
//     'type' => 'User',
//     'site_admin' => false,
//   ),
// )
