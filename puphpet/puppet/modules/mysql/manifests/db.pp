# See README.md for details.
define mysql::db (
  $user,
  $password,
  $dbname      = $name,
  $charset     = 'utf8',
  $collate     = 'utf8_general_ci',
  $host        = 'localhost',
  $grant       = 'ALL',
  $sql         = '',
  $enforce_sql = false,
  $ensure      = 'present'
) {
  #input validation
  validate_re($ensure, '^(present|absent)$',
  "${ensure} is not supported for ensure. Allowed values are 'present' and 'absent'.")
  $table = "${dbname}.*"

  # Detect "qualified" usernames and don't automatically append the specified host if one is present already. -bp
  if $user =~ /@/ {
    $userPlusHost = $user
  } else {
    $userPlusHost = "${user}@${host}"
  }

  include '::mysql::client'

  $db_resource = {
    ensure   => $ensure,
    charset  => $charset,
    collate  => $collate,
    provider => 'mysql',
    require  => [ Class['mysql::server'], Class['mysql::client'] ],
  }
  ensure_resource('mysql_database', $dbname, $db_resource)

  $user_resource = {
    ensure        => $ensure,
    password_hash => mysql_password($password),
    provider      => 'mysql',
    require       => Class['mysql::server'],
  }
  ensure_resource('mysql_user', "${userPlusHost}", $user_resource)  # -bp

  if $ensure == 'present' {
    mysql_grant { "${userPlusHost}/${table}":  # -bp
      privileges => $grant,
      provider   => 'mysql',
      user       => "${userPlusHost}",  # -bp
      table      => $table,
      require    => [Mysql_database[$dbname], Mysql_user["${userPlusHost}"], Class['mysql::server'] ],  # -bp
    }

    $refresh = ! $enforce_sql

    if $sql {
      exec{ "${dbname}-import":
        command     => "/usr/bin/mysql ${dbname} < ${sql}",
        logoutput   => true,
        environment => "HOME=${::root_home}",
        refreshonly => $refresh,
        require     => Mysql_grant["${userPlusHost}/${table}"], # -bp
        subscribe   => Mysql_database[$dbname],
      }
    }
  }
}
