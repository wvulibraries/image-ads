#!/usr/bin/env puma

# pre-defined puma configs
# =============================================================================
threads_count = ENV.fetch("RAILS_MAX_THREADS") { 5 }.to_i
threads threads_count, threads_count
# port        ENV.fetch("PORT") { 3000 }
# environment ENV.fetch("RAILS_ENV") { "development" }

# ssl puma configs
# =============================================================================
path_to_key = '/etc/httpd/ssl/Rotating-Homepage-Ads.key'
path_to_cert = '/etc/httpd/ssl/Rotating-Homepage-Ads.crt'

ssl_bind '0.0.0.0', '3000', {
  key: path_to_key,
  cert: path_to_cert,
  verify_mode: 'none'
}

# low log level of responces
# =============================================================================
quiet

# rails restart command
plugin :tmp_restart
