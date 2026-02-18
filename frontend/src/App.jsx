import { useEffect, useState } from 'react'

export default function App() {
  const [backendMessage, setBackendMessage] = useState('')
  const [error, setError] = useState('')

  useEffect(() => {
    let cancelled = false

    const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || ''

    async function load() {
      try {
        const res = await fetch(`${apiBaseUrl}/api/hello`)
        if (!res.ok) throw new Error(`Backend returned ${res.status}`)
        const data = await res.json()
        if (!cancelled) setBackendMessage(data.message ?? '')
      } catch (e) {
        if (!cancelled) setError(e instanceof Error ? e.message : String(e))
      }
    }

    load()

    return () => {
      cancelled = true
    }
  }, [])

  return (
    <div className="container">
      <h1>Hello World</h1>
      <h2>Frontend</h2>
      <p>React + Vite hosted on an AWS Amplify Instance</p>
      <p>Deployment: Autopulls from GitHub, builds using amplify.yml script, deploys to app.michael-blake.com</p>
      <h2>Backend</h2>
      <p>PHP/API hosted on an AWS EC2 Instance</p>
      <p>Deployment: Github Action triggers build in AWS ECR, then triggers an SSM command to deploy to the EC2 instance hosted at api.michael-blake.com</p>
      <p>CORS is handled by Nginx and only allowed from the frontend domain</p>
      <h2>TLDR</h2>
      <p>Push to GitHub, Everything else is automated</p>

      <div className="card">
        <h2>Backend response</h2>
        {error ? <pre className="error">{error}</pre> : <pre>{backendMessage || 'Loading...'}</pre>}
      </div>
    </div>
  )
}
