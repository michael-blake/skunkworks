import { useEffect, useState } from 'react'

export default function App() {
  const [backendMessage, setBackendMessage] = useState('')
  const [error, setError] = useState('')

  useEffect(() => {
    let cancelled = false

    async function load() {
      try {
        const res = await fetch('/api/hello')
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
      <p>Frontend: React + Vite</p>
      <p>Backend: PHP</p>

      <div className="card">
        <h2>Backend response</h2>
        {error ? <pre className="error">{error}</pre> : <pre>{backendMessage || 'Loading...'}</pre>}
      </div>
    </div>
  )
}
