/** @type {import('next').NextConfig} */
const nextConfig = {
    images: {
        deviceSizes: [400, 640, 800],
        loader: 'custom',
        loaderFile: 'imageLoader.js',
        remotePatterns: [
            {
              protocol: 'http',
              hostname: '127.0.0.1',
              port: '8000',
              pathname: '/**',
            },
            {
              protocol: 'https',
              hostname: '127.0.0.1',
              port: '8000',
              pathname: '/**',
            },
            {
              protocol: 'https',
              hostname: 'api.kingofpaddock.com',
              port: '',
              pathname: '/**',
            },
        ],
    },
    webpack(config) {
        config.module.rules.push({
          test: /\.svg$/i,
          issuer: /\.[jt]sx?$/,
          use: ['@svgr/webpack'],
        })
        return config
    },
};

export default nextConfig;
