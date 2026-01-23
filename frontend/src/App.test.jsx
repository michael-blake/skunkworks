import { describe, expect, it, vi, beforeEach } from 'vitest'
import { render, screen } from '@testing-library/react'
import App from './App.jsx'

describe('App', () => {
  beforeEach(() => {
    vi.restoreAllMocks()
  })

  it('renders the page header', () => {
    render(<App />)
    expect(screen.getByRole('heading', { name: /hello world/i })).toBeInTheDocument()
  })

  it('shows backend response message', async () => {
    global.fetch = vi.fn(async () => ({
      ok: true,
      json: async () => ({ message: 'Hello from PHP' }),
    }))

    render(<App />)

    expect(await screen.findByText('Hello from PHP')).toBeInTheDocument()
    expect(global.fetch).toHaveBeenCalledWith('/api/hello')
  })
})
